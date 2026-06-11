<?php

namespace App\Ordering\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'item_number' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }
}
