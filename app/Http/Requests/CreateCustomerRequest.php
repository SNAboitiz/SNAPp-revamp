<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CreateCustomerRequest extends FormRequest
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
                'required',
                'string',
            ],

            'short_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('customers','short_name')
            ],

            'customer_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('customers','customer_number')
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'customer-modal');
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
