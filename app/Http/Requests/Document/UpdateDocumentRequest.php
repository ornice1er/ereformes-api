<?php
namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'sometimes|required|string|max:255',
            'fichier' => 'sometimes|file|mimes:pdf,doc,docx,txt|max:10240',
            'dossier_id' => 'sometimes|required|exists:dossiers,id',
            'metadonnees' => 'nullable|array',
            'metadonnees.*.cle' => 'required_with:metadonnees|string',
            'metadonnees.*.valeur' => 'required_with:metadonnees|string',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.mimes' => 'Le fichier doit être au format : pdf, doc, docx ou txt.',
            'fichier.max' => 'La taille du fichier ne doit pas dépasser 10 Mo.',
            'dossier_id.required' => 'Le dossier de classement est obligatoire.',
            'dossier_id.exists' => 'Le dossier sélectionné est invalide.',
            'metadonnees.array' => 'Les métadonnées doivent être fournies sous forme de tableau.',
            'metadonnees.*.cle.required_with' => 'Chaque métadonnée doit avoir une clé.',
            'metadonnees.*.valeur.required_with' => 'Chaque métadonnée doit avoir une valeur.',
        ];
    }

        public function prepareForValidation()
{
    if ($this->has('metadonnees') && is_string($this->metadonnees)) {
        $json = json_decode($this->metadonnees, true);
        info( $json);
        if (is_array($json)) {
            $array = [];

            foreach ($json as $data) {
                if (!empty($data['cle']) && !empty($data['valeur'])) {
                    $array[] = [
                        'cle' => $data['cle'],
                        'valeur' => $data['valeur'],
                    ];
                }
            }

            $this->merge([
                'metadonnees' => $array
            ]);
        }
    }
}
}
