<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminUpdateProfileRequest extends FormRequest
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
            'edit_customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],

            'edit_facility_id' => [
                'nullable',
                'integer',
                'exists:facilities,id',
            ],

            'edit_business_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_facility_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_customer_category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_cooperation_period_start_date' => [
                'nullable',
                'date',
            ],

            'edit_cooperation_period_end_date' => [
                'nullable',
                'date',
            ],

            'edit_contract_price' => [
                'nullable',
                'string',
                'max:100',
            ],

            'edit_contracted_demand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'edit_certificate_of_contestability_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'edit_other_information' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_mobile_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'edit_email' => [
                'nullable',
                'email',
                'max:100',
            ],

            'edit_contact_name_1' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_designation_1' => [
                'nullable',
                'string',
                'max:255',
            ],

            'edit_mobile_number_1' => [
                'nullable',
                'string',
                'max:20',
            ],

            'edit_email_1' => [
                'nullable',
                'email',
                'max:100',
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'edit-customer-modal');
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
