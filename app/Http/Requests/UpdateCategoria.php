<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualizar una categoría de finanza.
     */
    public function rules(): array
    {
        return [
            'nombre_categoria' => 'required|string|max:255|unique:categorias_finanzas,nombre_categoria,' . $this->route('categoria'),
            'tipo_finanza' => 'required|string|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.unique' => 'Ya existe una categoría con este nombre.',
            'tipo_finanza.required' => 'Debe seleccionar el tipo de finanza.',
            'tipo_finanza.in' => 'El tipo de finanza debe ser "ingreso" o "gasto".',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
        ];
    }
}