<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit-customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer')->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customerId,
            'mobile' => 'required|string|max:15|unique:customers,mobile,' . $customerId,
            'status' => 'required|in:active,inactive',

            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'state_id' => 'required|exists:states,id',
            'pincode_id' => 'required|exists:pincodes,id',
            'country' => 'nullable|string|max:100',
        ];
    }
}
