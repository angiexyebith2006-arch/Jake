<?php

namespace App\Http\Controllers;

use App\Models\Autorizacione;
use App\Models\Reemplazo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AutorizacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Obtener autorizaciones PENDIENTES (fecha_autorizacion es null)
            $autorizaciones = Autorizacione::with([
                'reemplazo',
                'reemplazo.programacion',
                'reemplazo.programacion.actividad',
                'reemplazo.programacion.asignacion',
                'reemplazo.programacion.asignacion.usuario',
                'reemplazo.asignacionReemplazado.usuario',
                'reemplazo.asignacionReemplazoPor.usuario',
                'autorizador'
            ])
            ->whereNull('fecha_autorizacion') // Pendientes
            ->whereHas('reemplazo', function($query) {
                $query->where('estado', 'Pendiente');
            })
            ->orderBy('id_autorizacion', 'desc')
            ->get();

            return view('autorizaciones.index', compact('autorizaciones'));

        } catch (\Exception $e) {
            Log::error('Error en AutorizacioneController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las autorizaciones: ' . $e->getMessage());
        }
    }

    /**
     * Aprobar una autorización.
     */
    public function aprobar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $autorizacion = Autorizacione::with(['reemplazo', 'reemplazo.programacion'])->findOrFail($id);
            
            $request->validate([
                'observaciones' => 'nullable|string'
            ]);

            // Verificar que la autorización esté pendiente
            if (!is_null($autorizacion->fecha_autorizacion)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Esta autorización ya ha sido procesada.'
                ], 400);
            }

            // Verificar que el reemplazo asociado esté pendiente
            if ($autorizacion->reemplazo->estado !== 'Pendiente') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Este reemplazo ya ha sido procesado.'
                ], 400);
            }

            // Actualizar el reemplazo
            $autorizacion->reemplazo->update(['estado' => 'Aprobado']);

            // Actualizar autorización (marcar como aprobada con fecha)
            $autorizacion->update([
                'fecha_autorizacion' => Carbon::now(),
                'observaciones' => $request->observaciones
            ]);

            // Actualizar la programación con el nuevo servidor
            if ($autorizacion->reemplazo->programacion) {
                $autorizacion->reemplazo->programacion->update([
                    'id_asignacion' => $autorizacion->reemplazo->id_asignacion_reemplazo_por,
                    'estado' => 'Confirmado'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Autorización aprobada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en aprobar autorización: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar autorización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una autorización.
     */
    public function rechazar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $autorizacion = Autorizacione::with(['reemplazo', 'reemplazo.programacion'])->findOrFail($id);
            
            $request->validate([
                'observaciones' => 'required|string|min:10'
            ]);

            // Verificar que la autorización esté pendiente
            if (!is_null($autorizacion->fecha_autorizacion)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Esta autorización ya ha sido procesada.'
                ], 400);
            }

            // Verificar que el reemplazo asociado esté pendiente
            if ($autorizacion->reemplazo->estado !== 'Pendiente') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Este reemplazo ya ha sido procesado.'
                ], 400);
            }

            // Actualizar el reemplazo
            $autorizacion->reemplazo->update(['estado' => 'Rechazado']);

            // Actualizar autorización (marcar como rechazada con fecha)
            $autorizacion->update([
                'fecha_autorizacion' => Carbon::now(),
                'observaciones' => $request->observaciones
            ]);

            // Restaurar estado de la programación a Pendiente
            if ($autorizacion->reemplazo->programacion) {
                $autorizacion->reemplazo->programacion->update(['estado' => 'Pendiente']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Autorización rechazada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en rechazar autorización: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar autorización: ' . $e->getMessage()
            ], 500);
        }
    }
}