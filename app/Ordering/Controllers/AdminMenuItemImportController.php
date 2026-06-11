<?php

namespace App\Ordering\Controllers;

use App\Ordering\Requests\StoreMenuImportRequest;
use App\Ordering\Services\MenuImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminMenuItemImportController
{
    public function create(): Response
    {
        return inertia('Admin/MenuItems/Import');
    }

    public function preview(StoreMenuImportRequest $request, MenuImportService $service): Response
    {
        $rows = $service->parse($request->file('file'));
        $service->validate($rows);

        return inertia('Admin/MenuItems/Import', [
            'rows' => $rows,
            'validCount' => count(array_filter($rows, fn ($r) => empty($r['errors']) && empty($r['skip_reason']))),
            'errorCount' => count(array_filter($rows, fn ($r) => ! empty($r['errors']))),
            'skipCount' => count(array_filter($rows, fn ($r) => ! empty($r['skip_reason']))),
        ]);
    }

    public function store(Request $request, MenuImportService $service): RedirectResponse
    {
        $rows = json_decode($request->input('rows'), true);

        if (! is_array($rows)) {
            return redirect()->route('admin.ordering.menu-items.import')
                ->with('error', 'Ungültige Daten.');
        }

        $result = $service->import($rows);

        return redirect()->route('admin.ordering.menu-items.index')
            ->with('success', "Import abgeschlossen: {$result['created']} erstellt, {$result['skipped']} übersprungen, {$result['errors']} Fehler.");
    }
}
