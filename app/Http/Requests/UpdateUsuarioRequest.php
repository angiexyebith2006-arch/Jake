<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambiar si quieres agregar permisos
    }

    public function rules()
    {
        return [
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'activo'   => 'nullable|boolean',
            'id_funcion' => 'required|integer',
            // 'clave' => 'nullable|string|min:6', // solo si quieres permitir cambiarla
        ];
    }

    public function prepareForValidation()
    {
        // Asegurarse de que 'activo' sea boolean
        $this->merge([
            'activo' => $this->has('activo') ? true : false,
        ]);
    }


    public function messages()
    {
        return [
            'nombre.required'   => 'El nombre es obligatorio.',
            'correo.required'   => 'El correo es obligatorio.',
            'correo.email'      => 'Debe ingresar un correo válido.',
            'correo.unique'     => 'Este correo ya está registrado.',
            'telefono.max'      => 'El teléfono no puede superar 20 caracteres.',
            'activo.required'   => 'Seleccione el estado.',

            //Mensajes para la clave
            'clave.min'         => 'La clave debe tener al menos 6 caracteres.',
            'clave.max'         => 'La clave no puede superar 255 caracteres.',
        ];
    }
}
