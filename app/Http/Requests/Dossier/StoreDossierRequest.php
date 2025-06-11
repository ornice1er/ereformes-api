<?php
namespace App\Http\Requests\Dossier;

use Illuminate\Foundation\Http\FormRequest;

class StoreDossierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dossiers,id',
            'regle_conservation_id' => 'required|exists:regle_conservations,id',
            'visibilite' => 'required|in:publique,restreinte',
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
            'règle_conservation_id.required' => 'La règle de conservation est obligatoire.',
            'règle_conservation_id.exists' => 'La règle de conservation sélectionnée est invalide.',
            'visibilité.required' => 'La visibilité est obligatoire.',
            'visibilité.in' => 'La visibilité doit être soit "publique" soit "restreinte".',
            'types_autorisés.array' => 'Les types autorisés doivent être une liste.',
            'types_autorisés.*.string' => 'Chaque type autorisé doit être une chaîne de caractères.',
        ];
    }
}
