<?php

namespace App\Http\Requests\Product\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'sometimes|string|max:255',
            'parent_id' => [
                'sometimes',
                'nullable',
                'exists:categories,id',
                Rule::notIn([(int) $this->route('id')]),
            ],
            'image_path' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean'
        ];
    }
}
