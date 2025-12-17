<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AsignacionesController extends Controller
{
    // ======================================================
    // LISTAR ASIGNACIONES + PROGRAMACIONES
    // ======================================================
    public function index()
    {
        try {
            $asignaciones = DB::table('asignaciones as a')
                ->select(
                    'a.id_asignacion',
                    'a.activo',
                    'u.nombre as nombre_usuario',
                    'm.nombre_ministerio',
                    'r.nombre_rol'
                )
                ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
                ->join('ministerios as m', 'a.id_ministerio', '=', 'm.id_ministerio')
                ->join('roles as r', 'a.id_rol', '=', 'r.id_rol')
                ->orderBy('u.nombre')
                ->get();

            // Cargar programaciones asociadas
            foreach ($asignaciones as $asignacion) {
                $asignacion->programaciones = DB::table('programaciones as p')
                    ->select(
                        'p.id_programacion',
                        'p.fecha',
                        'p.hora_inicio',
                        'p.hora_fin',
                        'p.estado',
                        'p.confirmado',
                        'act.nombre_actividad'
                    )
                    ->join('actividades as act', 'p.id_actividad', '=', 'act.id_actividad')
                    ->where('p.id_asignacion', $asignacion->id_asignacion)
                    ->orderBy('p.fecha', 'desc')
                    ->get();
            }

            return view('asignaciones.index', compact('asignaciones'));

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@index: ' . $e->getMessage());

            return view('asignaciones.index', [
                'asignaciones' => collect()
            ])->with('error', 'Error al cargar asignaciones');
        }
    }

    // ======================================================
    // CONFIRMAR ASISTENCIA (PROGRAMACIÓN)
    // ======================================================
    public function confirmarAsistencia($id_programacion)
    {
        try {
            $actualizado = DB::table('programaciones')
                ->where('id_programacion', $id_programacion)
                ->update([
                    'confirmado' => true,
                    'estado' => 'Confirmado'
                ]);

            if ($actualizado) {
                return back()->with('success', 'Asistencia confirmada');
            }

            return back()->with('error', 'No se pudo confirmar la asistencia');

        } catch (\Exception $e) {
            Log::error('Error confirmar asistencia: ' . $e->getMessage());
            return back()->with('error', 'Error al confirmar asistencia');
        }
    }

    // ======================================================
    // SOLICITAR REEMPLAZO (PROGRAMACIÓN)
    // ======================================================
    public function solicitarReemplazo(Request $request)
    {
        try {
            $request->validate([
                'id_programacion' => 'required|exists:programaciones,id_programacion',
                'id_asignacion_reemplazo_por' => 'required|exists:asignaciones,id_asignacion',
                'motivo' => 'nullable|string|max:200'
            ]);

            $programacion = DB::table('programaciones')
                ->where('id_programacion', $request->id_programacion)
                ->first();

            if (!$programacion) {
                return back()->with('error', 'Programación no encontrada');
            }

            DB::table('reemplazos')->insert([
                'id_programacion' => $request->id_programacion,
                'id_asignacion_reemplazado' => $programacion->id_asignacion,
                'id_asignacion_reemplazo_por' => $request->id_asignacion_reemplazo_por,
                'motivo' => $request->motivo,
                'fecha_solicitud' => Carbon::today(),
                'estado' => 'Pendiente'
            ]);

            return back()->with('success', 'Reemplazo solicitado correctamente');

        } catch (\Exception $e) {
            Log::error('Error solicitar reemplazo: ' . $e->getMessage());
            return back()->with('error', 'Error al solicitar reemplazo');
        }
    }
}
