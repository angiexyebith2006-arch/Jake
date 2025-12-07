<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsignacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambiar a true para permitir la autorización
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
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_ministerio' => 'required|exists:ministerios,id_ministerio',
            'id_rol' => 'required|exists:roles,id_rol',
            'activo' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El campo usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
            'id_ministerio.required' => 'El campo ministerio es obligatorio.',
            'id_ministerio.exists' => 'El ministerio seleccionado no existe.',
            'id_rol.required' => 'El campo rol es obligatorio.',
            'id_rol.exists' => 'El rol seleccionado no existe.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id_usuario' => 'usuario',
            'id_ministerio' => 'ministerio',
            'id_rol' => 'rol',
            'activo' => 'estado activo'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Asegurar que el campo activo sea booleano
        $this->merge([
            'activo' => $this->boolean('activo')
        ]);
    }
}