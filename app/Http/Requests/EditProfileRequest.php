<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
            'account_name' => [
                'nullable',
                'string',
                'max:100'],

            'short_name' => [
                'nullable',
                'string',
                'max:100'],

            'business_address' => [
                'nullable',
                'string',
                'max:255'],

            'facility_address' => [
                'nullable',
                'string',
                'max:255'],

            'customer_category' => [
                'nullable',
                'string',
                'max:255'],

            'cooperation_start_date' => [
                'nullable',
                'date'],

            'cooperation_end_date' => [
                'nullable',
                'date'],

            'contract_price' => [
                'nullable',
                'string'],

            'contract_demand' => [
                'nullable',
                'string',
            ],

            'other_information' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }
}
