<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Customer fields
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'mobile' => 'required|string|unique:customers,mobile',
            'status' => 'required|in:active,inactive',

            // Addresses array validation
            'addresses' => 'required|array|min:1',
            'addresses.*.address_line_1' => 'required|string|max:500',
            'addresses.*.state_id' => 'required|exists:states,id',
            'addresses.*.district_id' => 'required|exists:districts,id',
            'addresses.*.city_id' => 'required|exists:cities,id',
            'addresses.*.pincode_id' => 'required|exists:pincodes,id',
            'addresses.*.type' => 'required|in:home,work,billing,shipping',

            // Default address index
            'default_address' => 'required|integer|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'addresses.required' => 'At least one address is required.',
            'addresses.min' => 'At least one address is required.',
            'addresses.*.address_line_1.required' => 'Address line is required for all addresses.',
            'addresses.*.city_id.required' => 'City is required for all addresses.',
            'addresses.*.district_id.required' => 'District is required for all addresses.',
            'addresses.*.state_id.required' => 'State is required for all addresses.',
            'addresses.*.pincode_id.required' => 'Pincode is required for all addresses.',
        ];
    }
}
