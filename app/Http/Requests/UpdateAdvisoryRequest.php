<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvisoryRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'edit_headline' => [
                'string',
                'max:100',
            ],
            'edit_description' => [
                'string',
                'max:255',
            ],

            'edit_content' => [
                'string',
                'max:255',
            ],
            'edit_attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'is_archive' => [
                'nullable',
                'boolean', // Accepts true/false, 1/0
            ],
            'edit_link' => [
                'nullable',
                'url',
            ],

        ];
    }
}
