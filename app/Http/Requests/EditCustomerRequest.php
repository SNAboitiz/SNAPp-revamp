<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditCustomerRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'edit_name' =>
            [
                'sometimes',
                'string',
                'max:255'
            ],

            'edit_email' =>
            [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'edit_customer_id' =>
            [
                'sometimes',
                'numeric',
                'exists:customers,id'

            ],

            'edit_facility_id' =>
            [
                'sometimes',
                'numeric',
                'exists:facilities,id'

            ],

        ];
    }
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
