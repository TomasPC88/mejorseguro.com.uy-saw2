<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Este campo es requerido',
            'email.email' => 'Esta dirección de correo no es válida',
            'email.exists' => 'Esta dirección de correo no se encuentra dentro de nuestra lista de usuarios suscritos',
        ];
    }
}
