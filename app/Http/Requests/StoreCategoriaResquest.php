<?php

namespace App\Models\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Cambiar a true si no implementas lógica de permisos aún
        return true;
    }

    /**
     * Reglas de validación para almacenar una categoría de finanza.
     */
    public function rules(): array
    {
        return [
            'nombre_categoria' => 'required|string|max:255|unique:categorias_finanzas,nombre_categoria',
            'tipo_finanza' => 'required|string|in:ingreso,gasto', // ejemplo de tipos posibles
            'descripcion' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Mensajes personalizados (opcional)
     */
    public function messages(): array
    {
        return [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.unique' => 'Esta categoría ya existe.',
            'tipo_finanza.required' => 'Debe seleccionar el tipo de finanza.',
            'tipo_finanza.in' => 'El tipo de finanza debe ser "ingreso" o "gasto".',
        ];
    }
}