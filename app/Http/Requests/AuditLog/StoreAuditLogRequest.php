<?php
namespace App\Http\Requests\AuditLog;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ou selon la logique de ton middleware
    }

    public function rules(): array
    {
        return [
            'action' => 'required|string|max:255',
            'document_id' => 'required|exists:documents,id',
            'détails' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Le type d\'action est obligatoire.',
            'document_id.required' => 'Le document ciblé est obligatoire.',
            'document_id.exists' => 'Le document ciblé est invalide.',
            'détails.string' => 'Les détails doivent être une chaîne de caractères.',
        ];
    }
}
