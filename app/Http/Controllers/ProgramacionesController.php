<?php

namespace App\Http\Controllers;

use App\Models\Programacion;
use App\Models\Actividad;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProgramacionesController extends Controller
{
    /**
     * Mostrar lista de programaciones.
     */
    public function index(Request $request)
    {
        $query = Programacion::with(['actividad.ministerio', 'asignacion.usuario', 'asignacion.rol']);

        // Filtros
        if ($request->has('fecha') && $request->fecha != '') {
            $query->where('fecha', $request->fecha);
        }

        if ($request->has('id_actividad') && $request->id_actividad != '') {
            $query->where('id_actividad', $request->id_actividad);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        // Por defecto mostrar programaciones futuras
        if (!$request->has('fecha')) {
            $query->where('fecha', '>=', now()->format('Y-m-d'));
        }

        $programaciones = $query->orderBy('fecha')
                               ->orderBy('hora_inicio')
                               ->get();

        $actividades = Actividad::with('ministerio')->get();

        return view('programaciones.index', compact('programaciones', 'actividades'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $actividades = Actividad::with('ministerio')->get();
        $asignaciones = Asignacion::with(['usuario', 'ministerio', 'rol'])
                                 ->where('activo', true)
                                 ->get();
        
        return view('programaciones.create', compact('actividades', 'asignaciones'));
    }

    /**
     * Guardar nueva programación.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_actividad' => 'required|exists:actividades,id_actividad',
                'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
                'fecha' => 'required|date',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'estado' => 'sometimes|in:Programado,Reemplazado,Cancelado'
            ]);

            // Verificar que la asignación pertenece al ministerio de la actividad
            $actividad = Actividad::find($validated['id_actividad']);
            $asignacion = Asignacion::find($validated['id_asignacion']);

            if ($actividad->id_ministerio != $asignacion->id_ministerio) {
                return redirect()->back()
                    ->with('error', 'La asignación seleccionada no pertenece al ministerio de la actividad.')
                    ->withInput();
            }

            // Verificar que no haya solapamiento de horarios para la misma asignación
            $solapamiento = Programacion::where('id_asignacion', $validated['id_asignacion'])
                ->where('fecha', $validated['fecha'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                          ->orWhereBetween('hora_fin', [$validated['hora_inicio'], $validated['hora_fin']])
                          ->orWhere(function ($q) use ($validated) {
                              $q->where('hora_inicio', '<=', $validated['hora_inicio'])
                                ->where('hora_fin', '>=', $validated['hora_fin']);
                          });
                })
                ->where('estado', '!=', 'Cancelado')
                ->exists();

            if ($solapamiento) {
                return redirect()->back()
                    ->with('error', 'Ya existe una programación para esta persona en el mismo horario.')
                    ->withInput();
            }

            Programacion::create($validated);

            return redirect()->route('programaciones.index')
                ->with('success', 'Programación creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la programación: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar programación específica.
     */
    public function show($id)
    {
        $programacion = Programacion::with([
            'actividad.ministerio', 
            'asignacion.usuario', 
            'asignacion.rol',
            'reemplazos.reemplazado.usuario',
            'reemplazos.reemplazoPor.usuario'
        ])->find($id);
        
        if (!$programacion) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Programación no encontrada.');
        }
        
        return view('programaciones.show', compact('programacion'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $programacion = Programacion::find($id);
        $actividades = Actividad::with('ministerio')->get();
        $asignaciones = Asignacion::with(['usuario', 'ministerio', 'rol'])
                                 ->where('activo', true)
                                 ->get();
       
        if (!$programacion) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Programación no encontrada.');
        }
        
        return view('programaciones.edit', compact('programacion', 'actividades', 'asignaciones'));
    }

    /**
     * Actualizar programación.
     */
    public function update(Request $request, $id)
    {
        $programacion = Programacion::find($id);
        
        if (!$programacion) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Programación no encontrada.');
        }

        try {
            $validated = $request->validate([
                'id_actividad' => 'required|exists:actividades,id_actividad',
                'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
                'fecha' => 'required|date',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'estado' => 'sometimes|in:Programado,Reemplazado,Cancelado'
            ]);

            // Verificar que la asignación pertenece al ministerio de la actividad
            $actividad = Actividad::find($validated['id_actividad']);
            $asignacion = Asignacion::find($validated['id_asignacion']);

            if ($actividad->id_ministerio != $asignacion->id_ministerio) {
                return redirect()->back()
                    ->with('error', 'La asignación seleccionada no pertenece al ministerio de la actividad.')
                    ->withInput();
            }

            // Verificar que no haya solapamiento de horarios (excluyendo la actual programación)
            $solapamiento = Programacion::where('id_asignacion', $validated['id_asignacion'])
                ->where('fecha', $validated['fecha'])
                ->where('id_programacion', '!=', $id)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                          ->orWhereBetween('hora_fin', [$validated['hora_inicio'], $validated['hora_fin']])
                          ->orWhere(function ($q) use ($validated) {
                              $q->where('hora_inicio', '<=', $validated['hora_inicio'])
                                ->where('hora_fin', '>=', $validated['hora_fin']);
                          });
                })
                ->where('estado', '!=', 'Cancelado')
                ->exists();

            if ($solapamiento) {
                return redirect()->back()
                    ->with('error', 'Ya existe otra programación para esta persona en el mismo horario.')
                    ->withInput();
            }

            $programacion->update($validated);

            return redirect()->route('programaciones.index')
                ->with('success', 'Programación actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la programación: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar programación.
     */
    public function destroy($id)
    {
        try {
            $programacion = Programacion::findOrFail($id);

            // Verificar si tiene reemplazos asociados
            if ($programacion->reemplazos()->exists()) {
                return redirect()->route('programaciones.index')
                    ->with('error', 'No se puede eliminar la programación porque tiene reemplazos asociados.');
            }

            $programacion->delete();

            return redirect()->route('programaciones.index')
                ->with('success', 'Programación eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Error al eliminar la programación: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar programación.
     */
    public function cancelar($id)
    {
        try {
            $programacion = Programacion::findOrFail($id);
            $programacion->update(['estado' => 'Cancelado']);

            return redirect()->route('programaciones.index')
                ->with('success', 'Programación cancelada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Error al cancelar la programación: ' . $e->getMessage());
        }
    }

    /**
     * Reactivar programación cancelada.
     */
    public function reactivar($id)
    {
        try {
            $programacion = Programacion::findOrFail($id);
            $programacion->update(['estado' => 'Programado']);

            return redirect()->route('programaciones.index')
                ->with('success', 'Programación reactivada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('programaciones.index')
                ->with('error', 'Error al reactivar la programación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar calendario de programaciones.
     */
    public function calendario(Request $request)
    {
        $mes = $request->has('mes') ? $request->mes : now()->format('Y-m');
        
        $programaciones = Programacion::with(['actividad.ministerio', 'asignacion.usuario', 'asignacion.rol'])
            ->where('fecha', 'like', $mes . '%')
            ->where('estado', '!=', 'Cancelado')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('fecha');

        return view('programaciones.calendario', compact('programaciones', 'mes'));
    }

    /**
     * Mostrar programaciones por ministerio.
     */
    public function porMinisterio($idMinisterio, Request $request)
    {
        $query = Programacion::with(['actividad', 'asignacion.usuario', 'asignacion.rol'])
            ->whereHas('actividad', function ($q) use ($idMinisterio) {
                $q->where('id_ministerio', $idMinisterio);
            });

        if ($request->has('fecha') && $request->fecha != '') {
            $query->where('fecha', $request->fecha);
        } else {
            $query->where('fecha', '>=', now()->format('Y-m-d'));
        }

        $programaciones = $query->orderBy('fecha')
                               ->orderBy('hora_inicio')
                               ->get();

        $ministerio = \App\Models\Ministerio::find($idMinisterio);

        return view('programaciones.por-ministerio', compact('programaciones', 'ministerio'));
    }

    /**
     * Mostrar programaciones por usuario.
     */
    public function porUsuario($idUsuario, Request $request)
    {
        $query = Programacion::with(['actividad.ministerio', 'asignacion.rol'])
            ->whereHas('asignacion', function ($q) use ($idUsuario) {
                $q->where('id_usuario', $idUsuario);
            });

        if ($request->has('fecha') && $request->fecha != '') {
            $query->where('fecha', $request->fecha);
        } else {
            $query->where('fecha', '>=', now()->format('Y-m-d'));
        }

        $programaciones = $query->orderBy('fecha')
                               ->orderBy('hora_inicio')
                               ->get();

        $usuario = \App\Models\Usuario::find($idUsuario);

        return view('programaciones.por-usuario', compact('programaciones', 'usuario'));
    }

    /**
     * Mostrar horario semanal.
     */
    public function horarioSemanal(Request $request)
    {
        $fechaInicio = $request->has('fecha_inicio') ? $request->fecha_inicio : now()->startOfWeek()->format('Y-m-d');
        
        $fechasSemana = [
            'lunes' => $fechaInicio,
            'martes' => Carbon::parse($fechaInicio)->addDay()->format('Y-m-d'),
            'miercoles' => Carbon::parse($fechaInicio)->addDays(2)->format('Y-m-d'),
            'jueves' => Carbon::parse($fechaInicio)->addDays(3)->format('Y-m-d'),
            'viernes' => Carbon::parse($fechaInicio)->addDays(4)->format('Y-m-d'),
            'sabado' => Carbon::parse($fechaInicio)->addDays(5)->format('Y-m-d'),
            'domingo' => Carbon::parse($fechaInicio)->addDays(6)->format('Y-m-d'),
        ];

        $programaciones = Programacion::with(['actividad.ministerio', 'asignacion.usuario', 'asignacion.rol'])
            ->whereBetween('fecha', [$fechaInicio, $fechasSemana['domingo']])
            ->where('estado', '!=', 'Cancelado')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(['fecha', function ($item) {
                // Agrupar por intervalos de hora para mejor visualización
                $hora = Carbon::parse($item->hora_inicio);
                return $hora->format('H:00') . ' - ' . $hora->addHour()->format('H:00');
            }]);

        return view('programaciones.horario-semanal', compact('programaciones', 'fechasSemana', 'fechaInicio'));
    }
}