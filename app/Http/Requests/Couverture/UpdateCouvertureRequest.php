<?php

namespace App\Http\Requests\Couverture;

use App\Utilities\Common;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCouvertureRequest extends FormRequest
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
                $couvertureId = $this->route('couverture') ? $this->route('couverture'): null;
        return [
            'lib_couvert' => [
                'required',
                'string',
                'max:100',
                'unique:couvertures,lib_couvert,' . $couvertureId
            ],
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
            'lib_couvert.required' => 'Le libellé de la couverture est obligatoire.',
            'lib_couvert.string' => 'Le libellé de la couverture doit être une chaîne de caractères.',
            'lib_couvert.max' => 'Le libellé de la couverture ne peut pas dépasser 100 caractères.',
            'lib_couvert.unique' => 'Cette couverture existe déjà dans le système.',
        ];
    }

    protected function prepareForValidation()
    {
        // Merge any additional data or modify data before validation
        // Example: $this->mergeIfMissing(['is_active' => false]);
    }
}
