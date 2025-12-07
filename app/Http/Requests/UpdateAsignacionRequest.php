<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsignacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $asignacionId = $this->route('asignacion'); // O el nombre de tu parámetro de ruta

        return [
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_ministerio' => 'required|exists:ministerios,id_ministerio',
            'id_rol' => 'required|exists:roles,id_rol',
            'fecha_asignacion' => 'required|date|after_or_equal:today',
            'activo' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
            'id_ministerio.required' => 'El ministerio es obligatorio.',
            'id_ministerio.exists' => 'El ministerio seleccionado no existe.',
            'id_rol.required' => 'El rol es obligatorio.',
            'id_rol.exists' => 'El rol seleccionado no existe.',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asignacion.date' => 'La fecha de asignación debe ser una fecha válida.',
            'fecha_asignacion.after_or_equal' => 'La fecha de asignación no puede ser anterior a hoy.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validación personalizada para evitar duplicados
            $exists = \App\Models\Asignacion::where('id_usuario', $this->id_usuario)
                ->where('id_ministerio', $this->id_ministerio)
                ->where('id_rol', $this->id_rol)
                ->where('id_asignacion', '!=', $this->route('asignacion'))
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'id_usuario', 
                    'Este usuario ya tiene esta asignación en el mismo ministerio y rol.'
                );
            }
        });
    }
}