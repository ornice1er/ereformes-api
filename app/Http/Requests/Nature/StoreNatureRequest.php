<?php

namespace App\Http\Requests\Nature;

use Illuminate\Foundation\Http\FormRequest;

class StoreNatureRequest extends FormRequest
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
            'libnature' => 'required|string|max:500|min:3|unique:natures,libnature',
        ];

    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'libnature.required' => 'Le libellé de la nature est obligatoire.',
            'libnature.string' => 'Le libellé de la nature doit être une chaîne de caractères.',
            'libnature.max' => 'Le libellé de la nature ne peut pas dépasser 500 caractères.',
            'libnature.min' => 'Le libellé de la nature doit contenir au moins 3 caractères.',
            'libnature.unique' => 'Cette nature existe déjà dans le système.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'libnature' => 'libellé de la nature',
        ];
    }
 /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        //

    }
}
