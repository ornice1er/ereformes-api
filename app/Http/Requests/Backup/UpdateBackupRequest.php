<?php

namespace App\Http\Requests\Backup;

use App\Utilities\Common;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBackupRequest extends FormRequest
{
    /**
     * Determine if the backup is authorized to make this request.
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
         $backupId = $this->route('backup') ? $this->route('backup')->id : null;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('backups', 'name')->ignore($backupId)
            ],
            'filename' => [
                'sometimes',
                'required',
                'string',
                'max:1000'
            ],
            'type' => [
                'sometimes',
                'required',
                'string',
                'in:doc,database,full,config'
            ],
            'size' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'is_active' => [
                'nullable',
                'boolean'
            ]
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
            // name
            'name.required' => 'Le nom de la sauvegarde est obligatoire.',
            'name.string' => 'Le nom de la sauvegarde doit être une chaîne de caractères.',
            'name.max' => 'Le nom de la sauvegarde ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Une sauvegarde avec ce nom existe déjà.',

            // filename
            'filename.required' => 'Le chemin du fichier est obligatoire.',
            'filename.string' => 'Le chemin du fichier doit être une chaîne de caractères.',
            'filename.max' => 'Le chemin du fichier ne peut pas dépasser 1000 caractères.',

            // type
            'type.required' => 'Le type de sauvegarde est obligatoire.',
            'type.string' => 'Le type de sauvegarde doit être une chaîne de caractères.',
            'type.in' => 'Le type de sauvegarde doit être : doc, database, full ou config.',

            // size
            'size.integer' => 'La taille du fichier doit être un nombre entier.',
            'size.min' => 'La taille du fichier ne peut pas être négative.',

            // description
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',

            // is_active
            'is_active.boolean' => 'Le statut actif doit être vrai ou faux.',
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
            'name' => 'nom de la sauvegarde',
            'filename' => 'chemin du fichier',
            'type' => 'type de sauvegarde',
            'size' => 'taille du fichier',
            'description' => 'description',
            'is_active' => 'statut actif',
        ];
    }


    protected function prepareForValidation()
    {
        // Merge any additional data or modify data before validation
        // Example: $this->mergeIfMissing(['is_active' => false]);
    }
}
