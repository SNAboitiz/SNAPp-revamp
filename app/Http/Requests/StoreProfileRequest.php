<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreProfileRequest extends FormRequest
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
            'business_address' => [
                'nullable',
                'string',
                'max:255'
            ],

            'facility_address' => [
                'nullable',
                'string',
                'max:255'
            ],

            'customer_category' => [
                'nullable',
                'string',
                'max:255'
            ],

            'cooperation_period_start_date' => [
                'nullable',
                'date'
            ],

            'cooperation_period_end_date' => [
                'nullable',
                'date'
            ],

            'contract_price' => [
                'nullable',
                'string',
                'max:100'
            ],

            'contracted_demand' => [
                'nullable',
                'string',
                'max:100'
            ],

            'certificate_of_contestability_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'other_information' => [
                'nullable',
                'string',
                'max:255'
            ],

            'contact_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255'
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:20'
            ],

            'email' => [
                'nullable',
                'email',
                'max:100'
            ],

            // Secondary contact fields
            'contact_name_1' => [
                'nullable',
                'string',
                'max:255'
            ],

            'designation_1' => [
                'nullable',
                'string',
                'max:255'
            ],

            'mobile_number_1' => [
                'nullable',
                'string',
                'max:20'
            ],

            'email_1' => [
                'nullable',
                'email',
                'max:100'
            ],

            'account_executive' => [
                'nullable',
                'string',
                'max:100'
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'customer-profile-modal');
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
