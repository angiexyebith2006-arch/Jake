<?php namespace App\Http\Requests; 

use Illuminate\Foundation\Http\FormRequest; 

class StoreProgramacionRequest extends FormRequest 
{ 
    public function authorize(): bool 
    { 
{        return true; 
    } 

    public function rules(): array 
    { 
        return [ 
            'id_actividad'  => 'required|exists:actividades,id_actividad', 
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion', 
            'fecha'         => 'required|date', 
            'hora_inicio'   => 'required', 
            'hora_fin'      => 'required|after:hora_inicio', 
            'estado'        => 'required|in:Reemplazado,Confirmado,Pendiente', 
        ]; 
    } 
}
}