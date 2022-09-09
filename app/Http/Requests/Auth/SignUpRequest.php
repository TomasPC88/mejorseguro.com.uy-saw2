<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class SignUpRequest extends BaseRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Este campo es requerido',
            'email.email' => 'Esta dirección de correo no es válida',
            'email.unique' => 'Lo sentimos, esta dirección de correo está en uso',
            'password.min' => 'Su contraseña debe tener como mínimo 8 caracteres',
            'confirm.same' => 'Las contraseñas no coinciden',
        ];
    }

}
