<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules = [
            'code' => 'required|min:3|max:255|unique:categories,code',
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:3',
            'name_en' => 'nullable|min:3|max:255',
            'description_en' => 'nullable|min:3',
        ];

        // If the route is not store (it's edit), we remove the unique rule for code
        if ($this->route()->named('categories.update')) {
            $rules['code'] = 'required|min:3|max:255|unique:categories,code,' . $this->route('category');
        }

        return $rules;
    }

    /**
     * Custom error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min' => 'Поле :attribute должно иметь минимум :min символов',
            'max' => 'Поле :attribute не может превышать :max символов',
            'unique' => 'Поле :attribute должно быть уникальным',
            'code.unique' => 'Код уже существует, выберите другой',
            'name_en.min' => 'Поле name_en должно содержать минимум :min символов',
            'name_en.max' => 'Поле name_en не может превышать :max символов',
            'description_en.min' => 'Поле description_en должно содержать минимум :min символов',
            'description_en.max' => 'Поле description_en не может превышать :max символов',
        ];
    }
}
