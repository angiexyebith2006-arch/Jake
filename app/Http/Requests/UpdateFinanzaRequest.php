<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanzaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualizar un movimiento de finanza.
     */
    public function rules(): array
    {
        return [
            'id_categoria' => 'sometimes|exists:categorias_finanzas,id_categoria',
            'monto' => 'sometimes|numeric|min:0.01',
            'fecha' => 'sometimes|date',
            'descripcion' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'id_categoria.exists' => 'La categoría no existe.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'fecha.date' => 'La fecha no es válida.',
            'descripcion.max' => 'La descripción no puede exceder 255 caracteres.',
        ];
    }
}