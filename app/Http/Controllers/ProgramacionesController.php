<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProgramacionesController extends Controller
{

    // LISTAR PROGRAMACIONES
    public function index(Request $request)
    {
        try {
            // Construir consulta base con joins para obtener toda la información
            $query = DB::table('programaciones as p')
                ->select(
                    'p.id_programacion',
                    'p.fecha',
                    'p.hora_inicio',
                    'p.hora_fin',
                    'p.confirmado',
                    'p.estado',
                    'a.id_actividad',
                    'a.nombre_actividad',
                    'm.id_ministerio',
                    'm.nombre_ministerio',
                    'asig.id_asignacion',
                    'u.id_usuario',
                    'u.nombre as nombre_usuario',
                    'r.id_rol',
                    'r.nombre_rol'
                )
                ->join('actividades as a', 'p.id_actividad', '=', 'a.id_actividad')
                ->join('ministerios as m', 'a.id_ministerio', '=', 'm.id_ministerio')
                ->join('asignaciones as asig', 'p.id_asignacion', '=', 'asig.id_asignacion')
                ->join('usuarios as u', 'asig.id_usuario', '=', 'u.id_usuario')
                ->join('roles as r', 'asig.id_rol', '=', 'r.id_rol')
                ->orderBy('p.fecha', 'desc')
                ->orderBy('p.hora_inicio', 'asc');

            // Aplicar filtros
            if ($request->has('estado') && $request->estado) {
                $query->where('p.estado', $request->estado);
            }

            if ($request->has('fecha') && $request->fecha) {
                $query->where('p.fecha', $request->fecha);
            }

            // Obtener resultados
            $programaciones = $query->get()->map(function ($item) {
                return (object) [
                    'idProgramacion' => $item->id_programacion,
                    'fecha' => $item->fecha,
                    'horaInicio' => $item->hora_inicio,
                    'horaFin' => $item->hora_fin,
                    'estado' => $item->estado,
                    'confirmado' => (bool) $item->confirmado,
                    'nombreActividad' => $item->nombre_actividad,
                    'idMinisterioAsignacion' => $item->nombre_ministerio,
                    'idUsuarioAsignacion' => $item->nombre_usuario,
                    'idRolAsignacion' => $item->nombre_rol,
                    // Datos adicionales para usar en otras vistas
                    'id_actividad' => $item->id_actividad,
                    'id_asignacion' => $item->id_asignacion
                ];
            });

            return view('programacion.index', compact('programaciones'));

        } catch (\Exception $e) {
            Log::error('Error en ProgramacionesController@index: ' . $e->getMessage());
            
            return view('programacion.index', [
                'programaciones' => collect()
            ])->with('error', 'Error al cargar las programaciones: ' . $e->getMessage());
        }
    }


    // FORMULARIO DE CREACIÓN
    public function create()
{
    try {
        // Obtener actividades desde base de datos
        $actividades = DB::table('actividades as a')
            ->select(
                'a.id_actividad',
                'a.nombre_actividad',
                'a.descripcion',
                'm.nombre_ministerio'
            )
            ->join('ministerios as m', 'a.id_ministerio', '=', 'm.id_ministerio')
            ->where('m.id_ministerio', '>', 0)
            ->orderBy('a.nombre_actividad')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id_actividad' => $item->id_actividad,
                    'nombre_actividad' => $item->nombre_actividad,
                    'descripcion' => $item->descripcion,
                    'ministerio' => $item->nombre_ministerio
                ];
            });

        // Obtener asignaciones activas con información completa
        $asignaciones = DB::table('asignaciones as asig')
            ->select(
                'asig.id_asignacion',
                'u.id_usuario',
                'u.nombre as nombre_usuario',
                'r.id_rol',
                'r.nombre_rol',
                'm.id_ministerio',
                'm.nombre_ministerio'
            )
            ->join('usuarios as u', 'asig.id_usuario', '=', 'u.id_usuario')
            ->join('roles as r', 'asig.id_rol', '=', 'r.id_rol')
            ->join('ministerios as m', 'asig.id_ministerio', '=', 'm.id_ministerio')
            ->where('asig.activo', true)
            ->where('u.activo', true)
            ->orderBy('u.nombre')
            ->get()
            ->map(function ($item) {
                return (object) [  // Cambiado de array a object
                    'id' => $item->id_asignacion,
                    'id_asignacion' => $item->id_asignacion,
                    'nombreUsuario' => $item->nombre_usuario,
                    'nombreRol' => $item->nombre_rol,
                    'nombreMinisterio' => $item->nombre_ministerio
                ];
            });

        // Si no hay asignaciones, crear un array vacío
        if ($asignaciones->isEmpty()) {
            Log::warning('No se encontraron asignaciones activas en la base de datos');
        }

        return view('programacion.create', [
            'actividades' => $actividades,
            'asignaciones' => $asignaciones
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error en ProgramacionesController@create: ' . $e->getMessage());
        
        return redirect()->route('programacion.index')
            ->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
    }
}

   // ======================================================
// GUARDAR PROGRAMACIÓN
// ======================================================
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'id_actividad' => 'required|integer|min:1|exists:actividades,id_actividad',
            'id_asignacion' => 'required|integer|min:1|exists:asignaciones,id_asignacion',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'estado' => 'nullable|in:Pendiente,Confirmado,Reemplazado'
        ]);

        // Verificar que la asignación esté activa
        $asignacionActiva = DB::table('asignaciones')
            ->where('id_asignacion', $request->id_asignacion)
            ->where('activo', true)
            ->exists();

        if (!$asignacionActiva) {
            return back()
                ->withInput()
                ->with('error', 'La asignación seleccionada no está activa');
        }

        // Verificar que no exista conflicto de horario
        $conflicto = DB::table('programaciones')
            ->where('id_asignacion', $request->id_asignacion)
            ->where('fecha', $request->fecha)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                      });
            })
            ->exists();

        if ($conflicto) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe una programación en el mismo horario para esta asignación');
        }

        // Insertar nueva programación (el id es automático)
        DB::table('programaciones')->insert([
            'id_actividad' => $request->id_actividad,
            'id_asignacion' => $request->id_asignacion,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => $request->estado ?? 'Pendiente',
            'confirmado' => ($request->estado == 'Confirmado') ? true : false
        ]);

        Log::info('Programación creada exitosamente');

        return redirect()->route('programacion.index')
            ->with('success', 'Programación creada correctamente');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()
            ->withErrors($e->validator)
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('Error en ProgramacionesController@store: ' . $e->getMessage());
        Log::error('Detalles del error:', ['exception' => $e]);
        
        return back()
            ->withInput()
            ->with('error', 'Error al crear programación: ' . $e->getMessage());
    }
}

   // ======================================================
// EDITAR
// ======================================================
public function edit($id)
{
    try {
        // Obtener programación específica desde base de datos
        $programacion = DB::table('programaciones as p')
            ->select(
                'p.id_programacion',
                'p.id_actividad',
                'p.id_asignacion',
                'p.fecha',
                'p.hora_inicio',
                'p.hora_fin',
                'p.estado',
                'p.confirmado',
                'a.nombre_actividad',
                'asig.id_usuario',
                'u.nombre as nombre_usuario',
                'r.nombre_rol',
                'm.nombre_ministerio'
            )
            ->join('actividades as a', 'p.id_actividad', '=', 'a.id_actividad')
            ->join('asignaciones as asig', 'p.id_asignacion', '=', 'asig.id_asignacion')
            ->join('usuarios as u', 'asig.id_usuario', '=', 'u.id_usuario')
            ->join('roles as r', 'asig.id_rol', '=', 'r.id_rol')
            ->join('ministerios as m', 'asig.id_ministerio', '=', 'm.id_ministerio')
            ->where('p.id_programacion', $id)
            ->first();

        if (!$programacion) {
            return redirect()->route('programacion.index')
                ->with('error', 'Programación no encontrada');
        }

        // Convertir a array para facilitar el acceso en la vista
        $programacionArray = [
            'id_programacion' => $programacion->id_programacion,
            'id_actividad' => $programacion->id_actividad,
            'id_asignacion' => $programacion->id_asignacion,
            'fecha' => $programacion->fecha,
            'hora_inicio' => $programacion->hora_inicio,
            'hora_fin' => $programacion->hora_fin,
            'estado' => $programacion->estado,
            'confirmado' => $programacion->confirmado,
            'nombre_actividad' => $programacion->nombre_actividad,
            'nombre_usuario' => $programacion->nombre_usuario,
            'nombre_rol' => $programacion->nombre_rol,
            'nombre_ministerio' => $programacion->nombre_ministerio
        ];

        // Obtener actividades para el select
        $actividades = DB::table('actividades')
            ->select('id_actividad', 'nombre_actividad')
            ->orderBy('nombre_actividad')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id_actividad' => $item->id_actividad,
                    'nombre_actividad' => $item->nombre_actividad
                ];
            });

        // Obtener asignaciones para el select
        $asignaciones = DB::table('asignaciones as asig')
            ->select(
                'asig.id_asignacion',
                'u.nombre as nombre_usuario',
                'r.nombre_rol',
                DB::raw("CONCAT(u.nombre, ' - ', r.nombre_rol) as texto")
            )
            ->join('usuarios as u', 'asig.id_usuario', '=', 'u.id_usuario')
            ->join('roles as r', 'asig.id_rol', '=', 'r.id_rol')
            ->where('asig.activo', true)
            ->orderBy('u.nombre')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id_asignacion' => $item->id_asignacion,
                    'nombre_usuario' => $item->nombre_usuario,
                    'nombre_rol' => $item->nombre_rol,
                    'texto' => $item->texto
                ];
            });

        return view('programacion.edit', [
            'programacion' => (object) $programacionArray, // Convertir a objeto
            'actividades' => $actividades,
            'asignaciones' => $asignaciones
        ]);

    } catch (\Exception $e) {
        Log::error('Error en ProgramacionesController@edit: ' . $e->getMessage());
        
        return redirect()->route('programacion.index')
            ->with('error', 'Error al cargar programación para editar: ' . $e->getMessage());
    }
}

    // ======================================================
    // ACTUALIZAR
    // ======================================================
  public function update(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'id_actividad' => 'required|integer|min:1|exists:actividades,id_actividad',
            'id_asignacion' => 'required|integer|min:1|exists:asignaciones,id_asignacion',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'estado' => 'nullable|in:Pendiente,Confirmado,Reemplazado'
        ]);

        // Verificar que la programación existe
        $programacionExiste = DB::table('programaciones')
            ->where('id_programacion', $id)
            ->exists();

        if (!$programacionExiste) {
            return redirect()->route('programacion.index')
                ->with('error', 'Programación no encontrada');
        }

        // Verificar que la asignación esté activa
        $asignacionActiva = DB::table('asignaciones')
            ->where('id_asignacion', $request->id_asignacion)
            ->where('activo', true)
            ->exists();

        if (!$asignacionActiva) {
            return back()
                ->withInput()
                ->with('error', 'La asignación seleccionada no está activa');
        }

        // Verificar conflicto de horario (excluyendo la programación actual)
        $conflicto = DB::table('programaciones')
            ->where('id_programacion', '!=', $id)
            ->where('id_asignacion', $request->id_asignacion)
            ->where('fecha', $request->fecha)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                      });
            })
            ->exists();

        if ($conflicto) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe otra programación en el mismo horario para esta asignación');
        }

        // Actualizar programación
        $actualizado = DB::table('programaciones')
            ->where('id_programacion', $id)
            ->update([
                'id_actividad' => $request->id_actividad,
                'id_asignacion' => $request->id_asignacion,
                'fecha' => $request->fecha,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'estado' => $request->estado ?? 'Pendiente',
                'confirmado' => ($request->estado == 'Confirmado') ? true : false
            ]);

        if ($actualizado) {
            return redirect()->route('programacion.index')
                ->with('success', 'Programación actualizada correctamente');
        } else {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la programación');
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()
            ->withErrors($e->validator)
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('Error en ProgramacionesController@update: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->with('error', 'Error al actualizar programación: ' . $e->getMessage());
    }
}
    // ======================================================
    // ELIMINAR
    // ======================================================
    public function destroy($id)
    {
        try {
            // Verificar si la programación existe
            $programacion = DB::table('programaciones')
                ->where('id_programacion', $id)
                ->first();

            if (!$programacion) {
                return redirect()->route('programacion.index')
                    ->with('error', 'Programación no encontrada');
            }

            // Verificar si tiene reemplazos asociados
            $tieneReemplazos = DB::table('reemplazos')
                ->where('id_programacion', $id)
                ->exists();

            if ($tieneReemplazos) {
                return redirect()->route('programacion.index')
                    ->with('error', 'No se puede eliminar la programación porque tiene reemplazos asociados');
            }

            // Eliminar programación
            $eliminado = DB::table('programaciones')
                ->where('id_programacion', $id)
                ->delete();

            if ($eliminado) {
                return redirect()->route('programacion.index')
                    ->with('success', 'Programación eliminada correctamente');
            } else {
                return redirect()->route('programacion.index')
                    ->with('error', 'No se pudo eliminar la programación');
            }

        } catch (\Exception $e) {
            Log::error('Error en ProgramacionesController@destroy: ' . $e->getMessage());
            
            return redirect()->route('programacion.index')
                ->with('error', 'Error al eliminar programación: ' . $e->getMessage());
        }
    }

    // ======================================================
    // CONFIRMAR PROGRAMACIÓN
    // ======================================================
    public function confirmar($id)
    {
        try {
            $actualizado = DB::table('programaciones')
                ->where('id_programacion', $id)
                ->update([
                    'confirmado' => true,
                    'estado' => 'Confirmado',
                    'updated_at' => now()
                ]);

            if ($actualizado) {
                return back()->with('success', 'Programación confirmada');
            } else {
                return back()->with('error', 'No se pudo confirmar la programación');
            }

        } catch (\Exception $e) {
            Log::error('Error en ProgramacionesController@confirmar: ' . $e->getMessage());
            
            return back()->with('error', 'Error al confirmar programación');
        }
    }

    // ======================================================
    // CANCELAR CONFIRMACIÓN
    // ======================================================
    public function cancelar($id)
    {
        try {
            $actualizado = DB::table('programaciones')
                ->where('id_programacion', $id)
                ->update([
                    'confirmado' => false,
                    'estado' => 'Pendiente',
                    'updated_at' => now()
                ]);

            if ($actualizado) {
                return back()->with('success', 'Confirmación cancelada');
            } else {
                return back()->with('error', 'No se pudo cancelar la confirmación');
            }

        } catch (\Exception $e) {
            Log::error('Error en ProgramacionesController@cancelar: ' . $e->getMessage());
            
            return back()->with('error', 'Error al cancelar confirmación');
        }
    }

    // ======================================================
    // MARCAR COMO REEMPLAZADA
    // ======================================================
    public function reemplazar($id)
    {
        try {
            $actualizado = DB::table('programaciones')
                ->where('id_programacion', $id)
                ->update([
                    'estado' => 'Reemplazado',
                    'updated_at' => now()
                ]);

            if ($actualizado) {
                return back()->with('success', 'Programación marcada como reemplazada');
            } else {
                return back()->with('error', 'No se pudo marcar como reemplazada');
            }

        } catch (\Exception $e) {
            Log::error('Error en ProgramacionesController@reemplazar: ' . $e->getMessage());
            
            return back()->with('error', 'Error al marcar como reemplazada');
        }
    }


    // OBTENER POR DÍA
    public function getByDay($dia)
    {
        try {
            $programaciones = DB::table('programaciones as p')
                ->select(
                    'p.id_programacion',
                    'p.fecha',
                    'p.hora_inicio',
                    'p.hora_fin',
                    'p.estado',
                    'p.confirmado',
                    'a.nombre_actividad',
                    'm.nombre_ministerio',
                    'u.nombre as nombre_usuario',
                    'r.nombre_rol'
                )
                ->join('actividades as a', 'p.id_actividad', '=', 'a.id_actividad')
                ->join('asignaciones as asig', 'p.id_asignacion', '=', 'asig.id_asignacion')
                ->join('usuarios as u', 'asig.id_usuario', '=', 'u.id_usuario')
                ->join('roles as r', 'asig.id_rol', '=', 'r.id_rol')
                ->join('ministerios as m', 'asig.id_ministerio', '=', 'm.id_ministerio')
                ->where('p.fecha', $dia)
                ->orderBy('p.hora_inicio')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $programaciones
            ]);

        } catch (\Exception $e) {
            Log::error('Error en getByDay: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener programaciones por día'
            ], 500);
        }
    }

    // ======================================================
    // OBTENER ESTADÍSTICAS
    // ======================================================
    public function getEstadisticas()
    {
        try {
            // Programaciones de hoy
            $hoy = DB::table('programaciones')
                ->whereDate('fecha', Carbon::today())
                ->count();

            // Servidores activos (usuarios con asignaciones activas)
            $servidores = DB::table('asignaciones as a')
                ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
                ->where('a.activo', true)
                ->where('u.activo', true)
                ->distinct('u.id_usuario')
                ->count('u.id_usuario');

            // Ministerios activos (con asignaciones activas)
            $ministerios = DB::table('asignaciones as a')
                ->join('ministerios as m', 'a.id_ministerio', '=', 'm.id_ministerio')
                ->where('a.activo', true)
                ->distinct('m.id_ministerio')
                ->count('m.id_ministerio');

            // Programaciones pendientes
            $pendientes = DB::table('programaciones')
                ->where('estado', 'Pendiente')
                ->whereDate('fecha', '>=', Carbon::today())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'hoy' => $hoy,
                    'servidores' => $servidores,
                    'ministerios' => $ministerios,
                    'pendientes' => $pendientes
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }
}