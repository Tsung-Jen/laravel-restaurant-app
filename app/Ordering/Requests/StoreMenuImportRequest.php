<?php

namespace App\Ordering\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }
}
