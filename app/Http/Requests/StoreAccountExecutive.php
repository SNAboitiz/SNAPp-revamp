<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAccountExecutive extends FormRequest
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
            'name' => [
                'required',
                'string', 

            ],
            'email' => [
                'required',
                'email', 
                Rule::unique(User::class)
            ],
            
            'customer_id' => [
                'required',
                'numeric',
                'exists:customers,id' 
            ],

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'create-accountexecutive'); 
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
