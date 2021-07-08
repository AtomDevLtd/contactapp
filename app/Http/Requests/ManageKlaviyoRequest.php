<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageKlaviyoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'klaviyo_api_key' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
