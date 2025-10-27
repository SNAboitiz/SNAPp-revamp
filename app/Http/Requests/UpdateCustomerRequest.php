<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'edit_account_name' => [
                'sometimes',
                'string',
            ],

            'edit_short_name' => [
                'sometimes',
                'string',
                'max:100',
            ],

            'edit_customer_number' => [
                'sometimes',
                'string',
                'max:50',
            ],
        ];
    }
     protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'edit-customer');
    
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
