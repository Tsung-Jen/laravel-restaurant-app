<?php

namespace App\Ordering\Services;

use App\Ordering\Models\Category;
use App\Ordering\Models\MenuItem;
use Illuminate\Http\UploadedFile;

class MenuImportService
{
    public function parse(UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (! $header) {
            fclose($handle);

            return [];
        }

        $header = array_map('trim', $header);
        $header = array_map('strtolower', $header);

        $rows = [];
        $rowNumber = 1;

        while (($line = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $data = array_combine($header, array_pad(array_map('trim', $line), count($header), ''));

            $rows[] = $this->makeRow($data, $rowNumber);
        }

        fclose($handle);

        return $rows;
    }

    private function makeRow(array $data, int $rowNumber): array
    {
        $itemNumber = $data['item_number'] ?? '';
        $categoryName = $data['category'] ?? '';
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $priceRaw = $data['price'] ?? '';
        $isActiveRaw = $data['is_active'] ?? '';
        $sortOrderRaw = $data['sort_order'] ?? '';

        $priceRaw = str_replace(',', '.', $priceRaw);

        return [
            'row_number' => $rowNumber,
            'item_number' => $itemNumber,
            'category_name' => $categoryName,
            'name' => $name,
            'description' => $description,
            'price' => is_numeric($priceRaw) ? (float) $priceRaw : null,
            'is_active' => in_array($isActiveRaw, ['1', 'true', 'yes'], true),
            'sort_order' => is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0,
            'errors' => [],
            'skip_reason' => null,
        ];
    }

    public function validate(array &$rows): void
    {
        $existingNumbers = MenuItem::pluck('item_number')->map(fn ($v) => strtolower($v))->toArray();

        foreach ($rows as &$row) {
            $errors = [];

            if (empty($row['item_number'])) {
                $errors[] = 'item_number fehlt';
            } elseif (in_array(strtolower($row['item_number']), $existingNumbers)) {
                $row['skip_reason'] = 'Artikelnummer existiert bereits';
            }

            if (empty($row['category_name'])) {
                $errors[] = 'Kategorie fehlt';
            }

            if (empty($row['name'])) {
                $errors[] = 'Name fehlt';
            }

            if ($row['price'] === null || $row['price'] < 0) {
                $errors[] = 'Preis ungültig';
            }

            if (strlen($row['item_number']) > 20) {
                $errors[] = 'Artikelnummer zu lang (max. 20)';
            }

            if (strlen($row['name']) > 255) {
                $errors[] = 'Name zu lang (max. 255)';
            }

            $row['errors'] = $errors;
        }

        unset($row);
    }

    public function import(array $rows): array
    {
        $created = 0;
        $skipped = 0;
        $errors = 0;
        $errorRows = [];

        foreach ($rows as $row) {
            if (! empty($row['errors'])) {
                $errors++;
                $errorRows[] = $row['row_number'];

                continue;
            }

            if ($row['skip_reason']) {
                $skipped++;

                continue;
            }

            $category = Category::firstOrCreate(
                ['name' => $row['category_name']],
                ['sort_order' => 0, 'is_active' => true],
            );

            MenuItem::create([
                'category_id' => $category->id,
                'item_number' => $row['item_number'],
                'name' => $row['name'],
                'description' => $row['description'] ?: null,
                'price' => $row['price'],
                'is_active' => $row['is_active'],
                'sort_order' => $row['sort_order'],
            ]);

            $created++;
        }

        return [
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
            'error_rows' => $errorRows,
        ];
    }
}
