<?php
namespace App\Http\Requests\RegleConservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegleConservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'duree_annees' => 'required|integer|min:1',
            'action_post_duree' => 'required|in:destruction,anonymisation',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la règle est obligatoire.',
            'durée_années.required' => 'La durée de conservation est obligatoire.',
            'durée_années.integer' => 'La durée doit être un entier.',
            'durée_années.min' => 'La durée doit être d’au moins une année.',
            'action_post_durée.required' => 'L’action après durée est obligatoire.',
            'action_post_durée.in' => 'L’action doit être "destruction" ou "anonymisation".',
        ];
    }
}
