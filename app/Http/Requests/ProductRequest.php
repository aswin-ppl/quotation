<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        // Use the correct permission depending on route
        if ($this->isMethod('post')) {
            return $this->user()->can('create-products');
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->user()->can('edit-products');
        }

        return false;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'size_mm' => 'required|string|max:100',
            'r_units' => 'required|string|max:100',
            'product_price' => 'required|numeric|min:0',
            'descriptions' => 'required|array',
            'descriptions.*.key' => 'required|string|max:255',
            'descriptions.*.value' => 'required|string|max:255',
        ];

        // For update, you might want to loosen or adjust a few things
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // For example: maybe image isn’t always re-uploaded
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }
}
