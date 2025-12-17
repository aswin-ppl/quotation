<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
        return [
            'company_name' => 'required|string|max:255',
            'company_mobile' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_pincode' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:255',
            'company_district' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
