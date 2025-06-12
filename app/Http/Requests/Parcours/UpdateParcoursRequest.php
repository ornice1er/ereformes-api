<?php

namespace App\Http\Requests\Parcours;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'libelle' => 'sometimes|required|string|max:255|min:3',
            'reforme_id' => 'sometimes|required|integer|exists:reformes,id',
        ];
    }

    /**
     * Messages d'erreur personnalisés en français
     *
     * @return array
     */
    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé du parcours est obligatoire.',
            'libelle.string' => 'Le libellé du parcours doit être une chaîne de caractères.',
            'libelle.max' => 'Le libellé du parcours ne peut pas dépasser 255 caractères.',
            'libelle.min' => 'Le libellé du parcours doit contenir au moins 3 caractères.',

            'reforme_id.required' => 'L\'identifiant de la réforme est obligatoire.',
            'reforme_id.integer' => 'L\'identifiant de la réforme doit être un nombre entier.',
            'reforme_id.exists' => 'La réforme sélectionnée n\'existe pas dans la base de données.',
        ];
    }

    /**
     * Noms des attributs personnalisés
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'libelle' => 'libellé du parcours',
            'reforme_id' => 'identifiant de la réforme',
        ];
    }

    /**
     * Préparer les données pour la validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {

    }
}
