<?php

namespace App\Http\Requests\Sector;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateSectorRequest extends FormRequest
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
            'libsecteur' => [
                'required',
                'string',
                'max:100',
                Rule::unique('secteur', 'libsecteur')->ignore($this->route('secteur')),
                'regex:/^[A-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØŒÙÚÛÜÝ\s\-\']+$/u'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'libsecteur.required' => 'Le libellé du secteur est obligatoire.',
            'libsecteur.string' => 'Le libellé du secteur doit être une chaîne de caractères.',
            'libsecteur.max' => 'Le libellé du secteur ne peut pas dépasser 100 caractères.',
            'libsecteur.unique' => 'Ce secteur existe déjà dans la base de données.',
            'libsecteur.regex' => 'Le libellé du secteur doit contenir uniquement des lettres majuscules, des espaces, des tirets et des apostrophes.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'libsecteur' => 'libellé du secteur'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {

    }

}
