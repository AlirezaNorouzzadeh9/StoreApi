<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => ['required', 'string', 'regex:/^(\+98|0)?9\d{9}$/'],
            'address_description' => 'required|string|max:500',
            'postal_code' => 'required|integer|digits:10',
            'plate_number' => 'nullable|string|max:20',
            'unit_number' => 'nullable|string|max:20',
            'city_id' => 'required|numeric|exists:cities,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'is_default' => 'sometimes|boolean',
        ];
    }
}
