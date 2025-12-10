<?php

namespace App\Http\Requests\Reforme;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReformeRequest extends FormRequest
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
            //'structure_id' => 'sometimes|integer|exists:structures,id',
            'nature_id' => 'sometimes|integer|exists:natures,id',
            'couverture_id' => 'sometimes|integer|exists:couvertures,id',
            'libref' => 'sometimes|string|max:500|min:10',
            'typeref' => 'sometimes|string|max:100',
            'objectif_glob' => 'sometimes|string',
            'popul_cible' => 'sometimes|string',
            'struct_impl' => 'sometimes|string',
            'periodexe' => 'sometimes|string',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date',
            'files' => 'sometimes|array',
            'date_enreg' => 'sometimes|date|before_or_equal:today',
            'cadreinst_mor' => 'sometimes|string|max:100|min:2',
            'etat_mor' => 'sometimes|string|in:EN COURS,TERMINÉ,SUSPENDU,ANNULÉ',
            'montant_prevu' => 'sometimes|numeric|min:0|max:999999999.99',
            'montant_trealise' => 'sometimes|numeric|min:0|max:999999999.99',
            'difficult' => 'sometimes|nullable|string',
            'solution' => 'sometimes|nullable|string',
            'perspective' => 'sometimes|nullable|string',
            'isPublished' => 'sometimes|boolean'
        ];
    }

    /**
     * Obtenir les messages d'erreur de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'structure_id.integer' => 'L\'identifiant de la structure doit être un nombre entier.',
            'structure_id.exists' => 'La structure sélectionnée n\'existe pas dans notre base de données.',

            'user_id.integer' => 'L\'identifiant de l\'utilisateur doit être un nombre entier.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas dans notre base de données.',

            'nature_id.integer' => 'L\'identifiant de la nature doit être un nombre entier.',
            'nature_id.exists' => 'La nature sélectionnée n\'existe pas dans notre base de données.',

            'couverture_id.integer' => 'L\'identifiant de la couverture doit être un nombre entier.',
            'couverture_id.exists' => 'La couverture sélectionnée n\'existe pas dans notre base de données.',

            'libref.string' => 'Le libellé de la réforme doit être une chaîne de caractères.',
            'libref.max' => 'Le libellé de la réforme ne peut pas dépasser 500 caractères.',
            'libref.min' => 'Le libellé de la réforme doit contenir au moins 10 caractères.',

            'typeref.string' => 'Le type de réforme doit être une chaîne de caractères.',
            'typeref.max' => 'Le type de réforme ne peut pas dépasser 100 caractères.',

            'objectif_glob.string' => 'L\'objectif global doit être une chaîne de caractères.',
            'objectif_glob.max' => 'L\'objectif global ne peut pas dépasser 2000 caractères.',
            'objectif_glob.min' => 'L\'objectif global doit contenir au moins 20 caractères.',

            'popul_cible.string' => 'La population cible doit être une chaîne de caractères.',
            'popul_cible.max' => 'La population cible ne peut pas dépasser 500 caractères.',
            'popul_cible.min' => 'La population cible doit contenir au moins 5 caractères.',

            'struct_impl.string' => 'La structure d\'implémentation doit être une chaîne de caractères.',
            'struct_impl.max' => 'La structure d\'implémentation ne peut pas dépasser 500 caractères.',
            'struct_impl.min' => 'La structure d\'implémentation doit contenir au moins 5 caractères.',

            'periodexe.string' => 'La période d\'exécution doit être une chaîne de caractères.',
            'periodexe.max' => 'La période d\'exécution ne peut pas dépasser 100 caractères.',
            'periodexe.min' => 'La période d\'exécution doit contenir au moins 5 caractères.',

            'date_debut.date' => 'La date de début doit être une date valide.',

            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',

            'date_enreg.date' => 'La date d\'enregistrement doit être une date valide.',
            'date_enreg.before_or_equal' => 'La date d\'enregistrement ne peut pas être future.',

            'cadreinst_mor.string' => 'Le cadre institutionnel doit être une chaîne de caractères.',
            'cadreinst_mor.max' => 'Le cadre institutionnel ne peut pas dépasser 100 caractères.',
            'cadreinst_mor.min' => 'Le cadre institutionnel doit contenir au moins 2 caractères.',

            'etat_mor.string' => 'L\'état doit être une chaîne de caractères.',
            'etat_mor.in' => 'L\'état doit être : EN COURS, TERMINÉ, SUSPENDU ou ANNULÉ.',

            'montant_prevu.numeric' => 'Le montant prévu doit être un nombre.',
            'montant_prevu.min' => 'Le montant prévu ne peut pas être négatif.',
            'montant_prevu.max' => 'Le montant prévu ne peut pas dépasser 999,999,999.99.',

            'montant_trealise.numeric' => 'Le montant réalisé doit être un nombre.',
            'montant_trealise.min' => 'Le montant réalisé ne peut pas être négatif.',
            'montant_trealise.max' => 'Le montant réalisé ne peut pas dépasser 999,999,999.99.',

            'difficult.string' => 'Les difficultés doivent être une chaîne de caractères.',
            'difficult.max' => 'Les difficultés ne peuvent pas dépasser 2000 caractères.',

            'solution.string' => 'Les solutions doivent être une chaîne de caractères.',
            'solution.max' => 'Les solutions ne peuvent pas dépasser 2000 caractères.',

            'perspective.string' => 'Les perspectives doivent être une chaîne de caractères.',
            'perspective.max' => 'Les perspectives ne peuvent pas dépasser 2000 caractères.',

            'isPublished.boolean' => 'Le statut de publication doit être vrai ou faux.'
        ];
    }

    /**
     * Obtenir les noms d'attributs personnalisés pour les erreurs de validation.
     */
    public function attributes(): array
    {
        return [
            'structure_id' => 'structure',
            'user_id' => 'utilisateur',
            'nature_id' => 'nature',
            'couverture_id' => 'couverture',
            'libref' => 'libellé de la réforme',
            'typeref' => 'type de réforme',
            'objectif_glob' => 'objectif global',
            'popul_cible' => 'population cible',
            'struct_impl' => 'structure d\'implémentation',
            'periodexe' => 'période d\'exécution',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'date_enreg' => 'date d\'enregistrement',
            'cadreinst_mor' => 'cadre institutionnel',
            'etat_mor' => 'état',
            'montant_prevu' => 'montant prévu',
            'montant_trealise' => 'montant réalisé',
            'difficult' => 'difficultés',
            'solution' => 'solutions',
            'perspective' => 'perspectives',
            'isPublished' => 'statut de publication'
        ];
    }

    /**
     * Préparer les données pour la validation.
     */
    protected function prepareForValidation(): void
    {

    }
}
