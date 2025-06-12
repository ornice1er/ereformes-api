<?php

namespace App\Http\Requests\Result;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'objectif_id' => [
                'required',
                'integer',
                'min:1',
                'exists:objectifs,id' // Ajustez le nom de la table si nécessaire
            ],
            'libresult' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'indicateur' => [
                'required',
                'string',
                'max:1000',
                'min:5'
            ],
            'valeur_cible' => [
                'required',
                'integer',
                'min:0'
            ],
            'valeurref' => [
                'required',
                'integer',
                'min:0'
            ],
            'status' => [
                'sometimes',
                'integer',
                'in:0,1' // 0 = inactif, 1 = actif
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'objectif_id.required' => 'L\'identifiant de l\'objectif est obligatoire.',
            'objectif_id.integer' => 'L\'identifiant de l\'objectif doit être un nombre entier.',
            'objectif_id.min' => 'L\'identifiant de l\'objectif doit être supérieur à 0.',
            'objectif_id.exists' => 'L\'objectif sélectionné n\'existe pas.',

            'libresult.required' => 'Le libellé du résultat est obligatoire.',
            'libresult.string' => 'Le libellé du résultat doit être une chaîne de caractères.',
            'libresult.max' => 'Le libellé du résultat ne peut pas dépasser 255 caractères.',
            'libresult.min' => 'Le libellé du résultat doit contenir au moins 3 caractères.',

            'indicateur.required' => 'L\'indicateur est obligatoire.',
            'indicateur.string' => 'L\'indicateur doit être une chaîne de caractères.',
            'indicateur.max' => 'L\'indicateur ne peut pas dépasser 1000 caractères.',
            'indicateur.min' => 'L\'indicateur doit contenir au moins 5 caractères.',

            'valeur_cible.required' => 'La valeur cible est obligatoire.',
            'valeur_cible.integer' => 'La valeur cible doit être un nombre entier.',
            'valeur_cible.min' => 'La valeur cible doit être supérieure ou égale à 0.',

            'valeurref.required' => 'La valeur de référence est obligatoire.',
            'valeurref.integer' => 'La valeur de référence doit être un nombre entier.',
            'valeurref.min' => 'La valeur de référence doit être supérieure ou égale à 0.',

            'status.integer' => 'Le statut doit être un nombre entier.',
            'status.in' => 'Le statut doit être soit 0 (inactif) soit 1 (actif).'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'objectif_id' => 'identifiant de l\'objectif',
            'libresult' => 'libellé du résultat',
            'indicateur' => 'indicateur',
            'valeur_cible' => 'valeur cible',
            'valeurref' => 'valeur de référence',
            'status' => 'statut'
        ];
    }
}
