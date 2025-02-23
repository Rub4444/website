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
        ];

        // If the route is not store (it's edit), we remove the unique rule for code
        if ($this->route()->named('categories.update')) {
            $rules['code'] = 'required|min:3|max:255|unique:categories,code,' . $this->route('category');
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min' => 'Поле :attribute должно иметь минимум :min символов',
            'max' => 'Поле :attribute не может превышать :max символов',
            'unique' => 'Поле :attribute должно быть уникальным',
            'code.unique' => 'Код уже существует, выберите другой',
        ];
    }
}
