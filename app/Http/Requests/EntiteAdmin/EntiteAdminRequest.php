<?php

namespace App\Http\Requests\EntiteAdmin;

use Illuminate\Foundation\Http\FormRequest;

class EntiteAdminRequest extends FormRequest
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
            'libelle' => 'required|string|max:255|min:2',
        ];

        // Règles spécifiques pour la mise à jour
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Vous pouvez ajouter des règles spécifiques pour la mise à jour
            $rules['libelle'] .= '|unique:entite_admins,libelle,' . $this->route('id');
        } else {
            // Règles pour la création
            $rules['libelle'] .= '|unique:entite_admins,libelle';
        }

        return $rules;
    }


 /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.string' => 'Le libellé doit être une chaîne de caractères.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
            'libelle.min' => 'Le libellé doit contenir au moins 2 caractères.',
            'libelle.unique' => 'Ce libellé existe déjà.',
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
            'libelle' => 'libellé',
        ];
    }
}
