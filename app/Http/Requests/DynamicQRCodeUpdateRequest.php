<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DynamicQRCodeUpdateRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'filename' => ['required', 'string'],
            'redirect_identifier' => ['required', 'string', 'unique:dynamic_q_r_codes,redirect_identifier'],
            'url' => ['required', 'string'],
            'foreground_color' => ['required', 'string'],
            'background_color' => ['required', 'string'],
            'precision' => ['required', 'in:L,M,Q,H'],
            'size' => ['required', 'integer'],
            'scan_count' => ['required', 'integer'],
            'status' => ['required'],
        ];
    }
}
