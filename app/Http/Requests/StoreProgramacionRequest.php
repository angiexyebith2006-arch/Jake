<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramacionRequest extends FormRequest
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
        return [
        'id_actividad' => $request->id_actividad,
        'id_asignacion' => $request->id_asignacion,
        'fecha' => $request->fecha,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'estado' => $request->estado,
        ];
    }
}
