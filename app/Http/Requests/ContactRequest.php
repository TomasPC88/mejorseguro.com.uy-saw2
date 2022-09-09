<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class ContactRequest extends BaseRequest
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
            'nombre' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'consulta' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => __('messages.nombre_required'),
            'telefono.required' => __('messages.telefono_required'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_email'),
            'consulta.required' => __('messages.consulta_required'),
        ];
    }

}
