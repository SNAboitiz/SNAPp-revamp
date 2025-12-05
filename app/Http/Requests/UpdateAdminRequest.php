<?php

namespace App\Http\Requests;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
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
            'edit_name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'edit_email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'edit_customer_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:customers,id'
            ],

            'edit_facility_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:facilities,id'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('edit_facility_id') && $this->filled('edit_customer_id')) {
                $facility = Facility::find($this->edit_facility_id);

                if ($facility && $facility->customer_id != $this->edit_customer_id) {
                    $validator->errors()->add(
                        'edit_facility_id',
                        'The selected facility does not belong to the chosen customer.'
                    );
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'edit-admin-modal');

        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
