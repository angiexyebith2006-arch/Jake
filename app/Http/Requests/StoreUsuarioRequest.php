<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true; // permitir siempre, o poner tu lógica de permisos
    }

    public function rules()
    {
        return [
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'activo'   => 'nullable|boolean',
            'clave'    => 'required|string|min:6',
        ];
    }

    public function prepareForValidation()
    {
        // Asegurarse de que 'activo' sea boolean
        $this->merge([
            'activo' => $this->has('activo') ? true : false,
        ]);
    }
    /**
     * Mensajes personalizados
     */
    public function messages()
    {
        return [
            'nombre.required'   => 'El nombre es obligatorio.',
            'correo.required'   => 'El correo es obligatorio.',
            'correo.unique'     => 'Este correo ya está registrado.',
            'correo.email'      => 'Debe ingresar un correo válido.',
            'telefono.max'      => 'El teléfono no puede superar 20 caracteres.',
            'activo.required'   => 'Debe seleccionar el estado.',
            
            //Mensajes de clave
            'clave.required'    => 'La clave es obligatoria.',
            'clave.min'         => 'La clave debe tener al menos 6 caracteres.',
        ];
    }
}
