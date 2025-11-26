<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditUserRequest extends FormRequest
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
                'sometimes',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user),

            ],
            'customer_id' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'role' => [
                'sometimes',
                'string',
                Rule::exists('roles', 'name'), // Validates role exists in roles table
            ],
            'active' => [
                'sometimes',
                'boolean', // Accepts true/false, 1/0
            ],
            'resend_welcome_email' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
