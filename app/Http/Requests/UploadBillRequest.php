<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBillRequest extends FormRequest
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
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],
            'facility_id' => [
                'nullable',
                'exists:facilities,id',
            ],

            'billing_start_date' => [
                'required',
                'date',
            ],
            'billing_end_date' => [
                'required',
                'date',
                'after_or_equal:billing_start_date',
            ],

            'bill_number' => [
                'required',
                'string',
                'max:255',
            ],
            'billing_start_date' => [
                'required',
                'date',
            ],
            'billing_end_date' => [
                'required',
                'date',
                'after_or_equal:billing_start_date',
            ],

            'bill_number' => [
                'required',
                'string',
                'max:255',
                'unique:bills,bill_number',
            ],
            'file_path' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240', // 10 MB
            ],
        ];
    }
}
