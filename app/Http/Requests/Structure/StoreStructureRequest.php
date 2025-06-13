<?php

namespace App\Http\Requests\Structure;

use Illuminate\Foundation\Http\FormRequest;

class StoreStructureRequest extends FormRequest
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
            'sector_id' => [
                'required',
                'integer',
                'exists:sectors,id'
            ],
            'sigl' => [
                'required',
                'string',
                'max:20',
                'unique:structure,sigl',
                'regex:/^[A-Z0-9\-_]+$/'
            ],
            'designation' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'adresse_struct' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'telephone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\d\s\+\-\(\)]+$/'
            ],
            'email' => [
                'nullable',
                'email',
                'max:150'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sector_id.required' => 'Le secteur est obligatoire.',
            'sector_id.integer' => 'L\'identifiant du secteur doit être un nombre entier.',
            'sector_id.exists' => 'Le secteur sélectionné n\'existe pas.',

            'sigl.required' => 'Le sigle est obligatoire.',
            'sigl.string' => 'Le sigle doit être une chaîne de caractères.',
            'sigl.max' => 'Le sigle ne peut pas dépasser 20 caractères.',
            'sigl.unique' => 'Ce sigle existe déjà dans la base de données.',
            'sigl.regex' => 'Le sigle doit contenir uniquement des lettres majuscules, des chiffres, des tirets et des underscores.',

            'designation.required' => 'La désignation est obligatoire.',
            'designation.string' => 'La désignation doit être une chaîne de caractères.',
            'designation.max' => 'La désignation ne peut pas dépasser 255 caractères.',
            'designation.min' => 'La désignation doit contenir au moins 3 caractères.',

            'adresse_struct.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse_struct.max' => 'L\'adresse ne peut pas dépasser 1000 caractères.',

            'telephone.string' => 'Le téléphone doit être une chaîne de caractères.',
            'telephone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'telephone.regex' => 'Le format du téléphone n\'est pas valide.',

            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 150 caractères.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'sector_id' => 'secteur',
            'sigl' => 'sigle',
            'designation' => 'désignation',
            'adresse_struct' => 'adresse de la structure',
            'telephone' => 'téléphone',
            'email' => 'adresse email'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {

    }

}
