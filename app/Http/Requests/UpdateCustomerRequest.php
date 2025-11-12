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
            // Customer fields
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customerId,
            'mobile' => 'required|string|max:15|unique:customers,mobile,' . $customerId,
            'status' => 'required|in:active,inactive',

            // Existing addresses
            'existing_addresses' => 'nullable|array',
            'existing_addresses.*.id' => 'required|exists:customer_addresses,id',
            'existing_addresses.*.keep' => 'required|in:0,1',

            // New addresses
            'new_addresses' => 'nullable|array',
            'new_addresses.*.address_line_1' => 'required|string|max:500',
            'new_addresses.*.state_id' => 'required|exists:states,id',
            'new_addresses.*.district_id' => 'required|exists:districts,id',
            'new_addresses.*.city_id' => 'required|exists:cities,id',
            'new_addresses.*.pincode_id' => 'required|exists:pincodes,id',
            'new_addresses.*.type' => 'required|in:home,work,billing,shipping',
            'new_addresses.*.is_new_default' => 'nullable|in:0,1',

            // Default address (can be existing ID or "new_X" for new addresses)
            'default_address' => 'required|string'
        ];
    }
}
