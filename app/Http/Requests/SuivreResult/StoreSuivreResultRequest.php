<?php

namespace App\Http\Requests\SuivreResult;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuivreResultRequest extends FormRequest
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
            'result_id' => [
                'required',
                'integer',
                'min:1',
                'exists:results,id'
            ],
            'taux_realisat' => [
                'required',
                'numeric',
                'min:0',
                'max:999.99' // Limite selon le type DECIMAL(5,2)
            ],
            'valeur_realise' => [
                'required',
                'integer',
                'min:0'
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
                // Contrainte d'unicité : un seul suivi par résultat et par date
                Rule::unique('suivi_results')->where(function ($query) {
                    return $query->where('result_id', $this->result_id);
                })
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
            'result_id.required' => 'L\'identifiant du résultat est obligatoire.',
            'result_id.integer' => 'L\'identifiant du résultat doit être un nombre entier.',
            'result_id.min' => 'L\'identifiant du résultat doit être supérieur à 0.',
            'result_id.exists' => 'Le résultat sélectionné n\'existe pas.',

            'taux_realisat.required' => 'Le taux de réalisation est obligatoire.',
            'taux_realisat.numeric' => 'Le taux de réalisation doit être un nombre.',
            'taux_realisat.min' => 'Le taux de réalisation doit être supérieur ou égal à 0.',
            'taux_realisat.max' => 'Le taux de réalisation ne peut pas dépasser 999.99.',

            'valeur_realise.required' => 'La valeur réalisée est obligatoire.',
            'valeur_realise.integer' => 'La valeur réalisée doit être un nombre entier.',
            'valeur_realise.min' => 'La valeur réalisée doit être supérieure ou égale à 0.',

            'date.required' => 'La date est obligatoire.',
            'date.date' => 'La date doit être une date valide.',
            'date.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'date.unique' => 'Un suivi existe déjà pour ce résultat à cette date.'
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
            'result_id' => 'identifiant du résultat',
            'taux_realisat' => 'taux de réalisation',
            'valeur_realise' => 'valeur réalisée',
            'date' => 'date'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {

    }
}
