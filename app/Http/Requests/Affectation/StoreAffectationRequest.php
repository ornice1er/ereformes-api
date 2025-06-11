<?php

namespace App\Http\Requests\Affectation;

use App\Utilities\Common;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAffectationRequest extends FormRequest
{
    /**
     * Determine if the affectation is authorized to make this request.
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
            'isLast' => 'required|boolean',
            'reforme_id' => 'required|integer|exists:reformes,id',
            'unite_admin_up' => 'required|integer',
            'unite_admin_down' => 'required|integer',
            'sens' => 'required|in:0,1',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'instruction' => 'nullable|string',
            'delay' => 'nullable|integer'
        ];
    }

    /**
     * Informations à afficher au cas où il y aurait des erreurs de validation
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Common::error($validator->errors()->first(), $validator->errors()));
    }

    /**
     * Mettre les messages d'erreur en Français
     *
     * @return array
     */
    public function messages()
    {
       return [
            // isLast
            'isLast.required' => 'Le statut "dernière affectation" est obligatoire.',
            'isLast.boolean' => 'Le statut "dernière affectation" doit être vrai ou faux.',

            // reforme_id
            'reforme_id.required' => 'L\'identifiant de la réforme est obligatoire.',
            'reforme_id.integer' => 'L\'identifiant de la réforme doit être un nombre entier.',
            'reforme_id.min' => 'L\'identifiant de la réforme doit être supérieur à 0.',
            'reforme_id.exists' => 'La réforme sélectionnée n\'existe pas dans le système.',

            // unite_admin_up
            'unite_admin_up.required' => 'L\'unité administrative supérieure est obligatoire.',
            'unite_admin_up.integer' => 'L\'unité administrative supérieure doit être un nombre entier.',
            'unite_admin_up.min' => 'L\'identifiant de l\'unité administrative supérieure doit être supérieur à 0.',
            'unite_admin_up.exists' => 'L\'unité administrative supérieure sélectionnée n\'existe pas.',

            // unite_admin_down
            'unite_admin_down.required' => 'L\'unité administrative inférieure est obligatoire.',
            'unite_admin_down.integer' => 'L\'unité administrative inférieure doit être un nombre entier.',
            'unite_admin_down.min' => 'L\'identifiant de l\'unité administrative inférieure doit être supérieur à 0.',
            'unite_admin_down.exists' => 'L\'unité administrative inférieure sélectionnée n\'existe pas.',
            'unite_admin_down.different' => 'L\'unité administrative inférieure doit être différente de l\'unité supérieure.',

            // sens
            'sens.required' => 'Le sens de l\'affectation est obligatoire.',
            'sens.integer' => 'Le sens de l\'affectation doit être un nombre entier.',
            'sens.in' => 'Le sens de l\'affectation doit être 0 (descendant) ou 1 (montant).',

            // instruction
            'instruction.string' => 'L\'instruction doit être une chaîne de caractères.',
            'instruction.max' => 'L\'instruction ne peut pas dépasser 5000 caractères.',

            // delay
            'delay.integer' => 'Le délai doit être un nombre entier.',
            'delay.min' => 'Le délai ne peut pas être négatif.',
            'delay.max' => 'Le délai ne peut pas dépasser 365 jours.',
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
            'isLast' => 'dernière affectation',
            'reforme_id' => 'réforme',
            'unite_admin_up' => 'unité administrative supérieure',
            'unite_admin_down' => 'unité administrative inférieure',
            'sens' => 'sens de l\'affectation',
            'instruction' => 'instruction',
            'delay' => 'délai',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        //

    }
}
