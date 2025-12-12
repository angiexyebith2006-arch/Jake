<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActividadRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Permitir la solicitud
    }

    /**
     * Reglas de validación para crear una actividad.
     */
    public function rules(): array
    {
        return [
            'id_ministerio' => 'required|integer|exists:ministerios,id_ministerio',
            'nombre_actividad' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'id_ministerio.required' => 'Debe seleccionar un ministerio.',
            'id_ministerio.exists' => 'El ministerio seleccionado no existe.',
            'nombre_actividad.required' => 'El nombre de la actividad es obligatorio.',
            'nombre_actividad.max' => 'El nombre es demasiado largo.',
            'descripcion.max' => 'La descripción no puede superar los 200 caracteres.',
        ];
    }
}
