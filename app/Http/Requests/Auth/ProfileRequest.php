<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ProfileRequest extends BaseRequest
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
        $rules = [
            'nombre' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'celular' => 'required|regex:/^09\d \d{3} \d{3}$/',
            'departamento' => 'required',
            'ciudad' => 'required',
            'direccion' => 'required',
            'codigo_postal' => 'required',
            'password' => 'min:8',
            'confirm' => 'same:password'
        ];

        $rules['email'] = Rule::unique('users')->ignore(auth()->user()->id);

        if ($this->request->has('envio') && $this->request->get('envio')) {
            $rules = array_merge($rules, [
                'envio_departamento' => 'required',
                'envio_ciudad' => 'required',
                'envio_direccion' => 'required',
                'envio_codigo_postal' => 'required',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            '*.required' => 'Este campo es requerido',
            'email.email' => 'Esta dirección de correo no es válida',
            'email.unique' => 'Lo sentimos, esta dirección de correo está en uso',
            'password.min' => 'Su contraseña debe tener como mínimo 8 caracteres',
            'confirm.same' => 'Las contraseñas no coinciden'
        ];
    }
}
