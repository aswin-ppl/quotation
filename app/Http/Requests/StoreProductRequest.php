<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer' => ['required', 'integer', 'exists:customers,id'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.size_mm' => ['nullable', 'string', 'max:255'],
            'products.*.cost_per_units' => ['required', 'numeric', 'min:0'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.product_price' => ['required', 'numeric', 'min:0'],
            'products.*.descriptions' => ['nullable', 'array'],
            'products.*.descriptions.*.key' => ['nullable', 'string', 'max:255'],
            'products.*.descriptions.*.value' => ['nullable', 'string', 'max:500'],
            'products.*.autocad_uploaded_name' => ['nullable', 'string'],
            'products.*.autocad_original_name' => ['nullable', 'string'],
            'products.*.extra_uploaded_names' => ['nullable', 'array'],
            'products.*.extra_uploaded_names.*' => ['string'],
            'products.*.extra_original_names' => ['nullable', 'array'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer.required' => 'Please select a customer.',
            'customer.exists' => 'The selected customer is invalid.',
            'discount.numeric' => 'Discount must be a valid number.',
            'discount.max' => 'Discount cannot exceed 100.',
            'remarks.max' => 'Remarks cannot exceed 1000 characters.',
            'products.required' => 'Please add at least one product.',
            'products.min' => 'Please add at least one product.',
            'products.*.name.required' => 'Product name is required.',
            'products.*.name.max' => 'Product name cannot exceed 255 characters.',
            'products.*.cost_per_units.required' => 'Cost per unit is required.',
            'products.*.cost_per_units.numeric' => 'Cost per unit must be a valid number.',
            'products.*.cost_per_units.min' => 'Cost per unit must be at least 0.',
            'products.*.quantity.required' => 'Product quantity is required.',
            'products.*.quantity.integer' => 'Product quantity must be a whole number.',
            'products.*.quantity.min' => 'Product quantity must be at least 1.',
            'products.*.product_price.required' => 'Product price is required.',
            'products.*.product_price.numeric' => 'Product price must be a valid number.',
            'products.*.product_price.min' => 'Product price must be at least 0.',
        ];
    }
}
