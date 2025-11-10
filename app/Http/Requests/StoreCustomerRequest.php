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

            // Address fields
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'state_id' => 'required|exists:states,id',
            'pincode_id' => 'required|exists:pincodes,id',
        ];
    }
}
