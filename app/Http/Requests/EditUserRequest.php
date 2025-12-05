<?php

namespace App\Http\Requests;

use App\Models\Facility;
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
                'max:255'
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)

            ],
            'customer_id' => [
                'sometimes',
                'nullable',
                'numeric',
                'exists:customers,id'
            ],

            'facility_id' => [
                'sometimes',
                'nullable',
                'numeric',
                'exists:facilities,id'
            ],

            'role' => [
                'sometimes',
                'string',
                Rule::exists('roles', 'name')
            ],
            'active' => [
                'sometimes',
                'boolean'
            ],
            'resend_welcome_email' => [
                'sometimes',
                'boolean'
            ]
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Only validate if BOTH customer_id and facility_id are provided
            if ($this->filled('customer_id') && $this->filled('facility_id')) {
                $facility = Facility::find($this->facility_id);

                // Check if facility exists and belongs to the selected customer
                if ($facility && $facility->customer_id != $this->customer_id) {
                    $validator->errors()->add(
                        'facility_id',
                        'The selected facility does not belong to the chosen customer.'
                    );
                }
            }
        });
    }
}
