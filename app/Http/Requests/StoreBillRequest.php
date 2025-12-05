<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'contract_start' => [
                'required',
                'date',
            ],
            'contract_end' => [
                'required',
                'date',
                'after_or_equal:contract_start',
            ],
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240',
            ],
            'description' => [
                'required',
                'string',
                'max:255',
            ],
            'short_name' => [
                'required',
                'string',
                'max:255',
            ],

        ];
    }
}
