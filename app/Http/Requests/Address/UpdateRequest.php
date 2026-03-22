<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'receiver_name' => 'sometimes|required|string|max:255',
            'receiver_phone' => 'sometimes|required|string|regex:/^(\+98|0)?9\d{9}$/',
            'province_id' => 'sometimes|required|exists:provinces,id',
            'city_id' => 'sometimes|required|exists:cities,id',
            'address_description' => 'sometimes|required|string|max:500',
            'postal_code' => 'sometimes|required|integer|digits:10',
            'plate_number' => 'sometimes|nullable|string|max:50',
            'unit_number' => 'sometimes|nullable|string|max:50',
            'is_default' => 'sometimes|boolean',
        ];
    }
}
