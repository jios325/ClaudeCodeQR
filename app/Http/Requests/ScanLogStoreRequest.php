<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanLogStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'qr_code_id' => ['required', 'integer', 'exists:qr_codes,id'],
            'qr_code_type' => ['required', 'in:dynamic,static'],
            'ip_address' => ['nullable', 'string'],
            'user_agent' => ['nullable', 'string'],
            'timestamp' => ['required'],
        ];
    }
}
