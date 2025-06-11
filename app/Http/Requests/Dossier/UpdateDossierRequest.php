<?php
namespace App\Http\Requests\Dossier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDossierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|exists:dossiers,id|different:id',
            'regle_conservation_id' => 'sometimes|required|exists:regle_conservations,id',
            'visibilite' => 'sometimes|required|in:publique,restreinte',
            'types_autorises' => 'nullable|array',
            'types_autorises.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du dossier est obligatoire.',
            'nom.max' => 'Le nom du dossier ne doit pas dépasser 255 caractères.',
            'parent_id.exists' => 'Le dossier parent sélectionné est invalide.',
            'parent_id.different' => 'Un dossier ne peut pas être son propre parent.',
            'règle_conservation_id.required' => 'La règle de conservation est obligatoire.',
            'règle_conservation_id.exists' => 'La règle de conservation sélectionnée est invalide.',
            'visibilité.required' => 'La visibilité est obligatoire.',
            'visibilité.in' => 'La visibilité doit être soit "publique" soit "restreinte".',
            'types_autorisés.array' => 'Les types autorisés doivent être une liste.',
            'types_autorisés.*.string' => 'Chaque type autorisé doit être une chaîne de caractères.',
        ];
    }
}
