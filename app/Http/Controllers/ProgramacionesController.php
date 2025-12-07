<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programacion;
use App\Models\Actividad;
use App\Models\Asignacion;

class ProgramacionesController extends Controller
{
    // Mostrar todas las programaciones
    public function index()
    {
        $programaciones = Programacion::with([
            'actividad',
            'asignacion.usuario',
            'asignacion.ministerio',
            'asignacion.rol'
        ])->get();

        return view('programacion.index', compact('programaciones'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $actividades = Actividad::all();
        $asignaciones = Asignacion::all();

        return view('programacion.create', compact('actividades', 'asignaciones'));
    }

    // Guardar nueva programación
    public function store(Request $request)
    {
        // Validación REAL según tu formulario
        $request->validate([
            'id_actividad' => 'required|exists:actividades,id_actividad',
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'estado' => 'required|in:Programado,Reemplazado,Cancelado'
        ]);

        // Crear programación
        Programacion::create([
            'id_actividad' => $request->id_actividad,
            'id_asignacion' => $request->id_asignacion,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => $request->estado
        ]);

        return redirect()->route('programacion.index')->with('success', 'Creado correctamente');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $programacion = Programacion::where('id_programacion', $id)->firstOrFail();
        $actividades = Actividad::all();
        $asignaciones = Asignacion::all();

        return view('programacion.edit', compact('programacion', 'actividades', 'asignaciones'));
    }

    // Actualizar programación
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_actividad' => 'required|exists:actividades,id_actividad',
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'estado' => 'required|in:Confirmado,Pendiente,Reemplazado'

        ]);

        $programacion = Programacion::where('id_programacion', $id)->firstOrFail();

        $programacion->update([
            'id_actividad' => $request->id_actividad,
            'id_asignacion' => $request->id_asignacion,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => $request->estado
        ]);

        return redirect()->route('programacion.index')->with('success', 'Actualizado correctamente');
    }

    // Eliminar programación
    public function destroy($id)
    {
        $programacion = Programacion::where('id_programacion', $id)->firstOrFail();
        $programacion->delete();

        return redirect()->route('programacion.index')->with('success', 'Eliminado correctamente');
    }
}
