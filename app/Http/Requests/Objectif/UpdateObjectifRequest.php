<?php

namespace App\Http\Requests\Objectif;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObjectifRequest extends FormRequest
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
            'reforme_id' => 'sometimes|required|integer|exists:reformes,id',
            'libobjectif' => 'sometimes|required|string|max:255|min:3',
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
            'reforme_id.required' => 'L\'identifiant de la réforme est obligatoire.',
            'reforme_id.integer' => 'L\'identifiant de la réforme doit être un nombre entier.',
            'reforme_id.exists' => 'La réforme sélectionnée n\'existe pas.',

            'libobjectif.required' => 'Le libellé de l\'objectif est obligatoire.',
            'libobjectif.string' => 'Le libellé de l\'objectif doit être une chaîne de caractères.',
            'libobjectif.max' => 'Le libellé de l\'objectif ne peut pas dépasser 255 caractères.',
            'libobjectif.min' => 'Le libellé de l\'objectif doit contenir au moins 3 caractères.',
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
            'reforme_id' => 'identifiant de la réforme',
            'libobjectif' => 'libellé de l\'objectif',
        ];
    }
}
