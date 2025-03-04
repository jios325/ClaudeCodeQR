<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaticQRCodeUpdateRequest extends FormRequest
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
            'content_type' => ['required', 'in:text,email,phone,sms,whatsapp,skype,location,vcard,event,bookmark,wifi,paypal,bitcoin,2fa'],
            'content' => ['required', 'string'],
            'foreground_color' => ['required', 'string'],
            'background_color' => ['required', 'string'],
            'precision' => ['required', 'in:L,M,Q,H'],
            'size' => ['required', 'integer'],
            'format' => ['required', 'in:png,svg,eps'],
        ];
    }
}
