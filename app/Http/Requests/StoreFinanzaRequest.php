<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanzaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        return true; //  IMPORTANTE
    }

    /**
     * Reglas de validación para crear un movimiento de finanza.
     */
    public function rules(): array
    {
        return [
            'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'id_categoria.required' => 'La categoría es obligatoria.',
            'id_categoria.exists' => 'La categoría no existe.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
        ];
    }
}