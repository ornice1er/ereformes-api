<?php

namespace App\Http\Requests\UserAuth;

use App\Utilities\Common;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|min:8',

        ];
    }

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
            'old_password.required' => "l'ancien mot de passe est requis.",
            'old_password.min' => "L'ancien mot de passe doit être au minimun 8 caracteres.",
            'old_password.min' => "L'ancien mot de passe doit être au minimun 8 caracteres.",
            'new_password.required' => 'Le nouveau mot  de passe  est requis.',
            'new_password.min' => 'Le nouveau mot de passe doit être au minimun 8 caracteres.',
            'new_password.confirmed' => 'Les mots de passe doivent être identiques  .',
            'new_password_confirmation.required' => 'Le nouveau mot  de passe  est requis.',
            'new_password_confirmation.min' => 'Le nouveau mot de passe doit être au minimun 8 caracteres.',

        ];
    }
}
