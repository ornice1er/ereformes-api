<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
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
            'projets_media_id' => 'required|integer|exists:projets_media,id',
            'chemin' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
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
            'projets_media_id.required' => 'L\'identifiant du projet média est obligatoire.',
            'projets_media_id.integer' => 'L\'identifiant du projet média doit être un nombre entier.',
            'projets_media_id.exists' => 'Le projet média sélectionné n\'existe pas.',
            'chemin.string' => 'Le chemin doit être une chaîne de caractères.',
            'chemin.max' => 'Le chemin ne peut pas dépasser 255 caractères.',
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
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
            'projets_media_id' => 'projet média',
            'chemin' => 'chemin d\'accès',
            'name' => 'nom du fichier',
        ];
    }
}
