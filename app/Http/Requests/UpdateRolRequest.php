<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre_rol' => 'required|string|max:100|unique:roles,nombre_rol,' . $this->route('rol'),
            'descripcion' => 'nullable|string|max:200',
        ];
    }

    public function messages()
    {
        return [
            'nombre_rol.required' => 'El nombre del rol es obligatorio.',
            'nombre_rol.unique' => 'Ya existe un rol con ese nombre.',
        ];
    }
}
