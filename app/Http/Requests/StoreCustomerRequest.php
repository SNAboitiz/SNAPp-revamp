<?php

namespace App\Http\Requests;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
                Rule::unique(User::class),
            ],

            'customer_id' => [
                'required',
                'integer',
                'exists:customers,id',
            ],

            'facility_id' => [
                'nullable',
                'numeric',
                'exists:facilities,id',
            ],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('facility_id') && $this->filled('customer_id')) {
                $facility = Facility::find($this->facility_id);

                if ($facility && $facility->customer_id != $this->customer_id) {
                    $validator->errors()->add(
                        'facility_id',
                        'The selected facility does not belong to the chosen customer.'
                    );
                }
            }
        });
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
