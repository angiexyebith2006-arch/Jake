<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programacion;
use App\Models\Reemplazo;
use App\Models\Asignacion;
use App\Models\Ministerio;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsignacionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener el ID del usuario autenticado
            $userId = Auth::id();
            
            \Log::info("=== DEBUG ASISTENCIA INDEX ===");
            \Log::info("Usuario autenticado ID: " . $userId);
            \Log::info("Usuario autenticado nombre: " . Auth::user()->nombre);

            // Obtener TODAS las asignaciones activas para debug
            $todasAsignaciones = Asignacion::with(['usuario', 'ministerio', 'rol'])
                ->where('activo', true)
                ->get();
            
            \Log::info("=== TODAS LAS ASIGNACIONES ACTIVAS ===");
            foreach($todasAsignaciones as $asig) {
                \Log::info("Asignación ID: {$asig->id_asignacion} | Usuario: {$asig->usuario->nombre} | Ministerio: {$asig->ministerio->nombre_ministerio} | Activo: {$asig->activo}");
            }

            // Obtener las asignaciones del usuario autenticado
            $asignacionesUsuario = Asignacion::where('id_usuario', $userId)
                ->where('activo', true)
                ->pluck('id_asignacion');

            \Log::info("=== ASIGNACIONES DEL USUARIO AUTENTICADO ===");
            \Log::info("Usuario ID: " . $userId);
            \Log::info("Asignaciones encontradas: " . $asignacionesUsuario->count());
            \Log::info("IDs de asignaciones: " . $asignacionesUsuario->implode(', '));

            // Obtener TODAS las programaciones para debug
            $todasProgramaciones = Programacion::with([
                'asignacion.usuario',
                'asignacion.ministerio', 
                'asignacion.rol',
                'actividad.ministerio'
            ])->get();

            \Log::info("=== TODAS LAS PROGRAMACIONES EN LA BD ===");
            foreach($todasProgramaciones as $prog) {
                \Log::info("Programación ID: {$prog->id_programacion} | Asignación ID: {$prog->id_asignacion} | Usuario: {$prog->asignacion->usuario->nombre} | Fecha: {$prog->fecha}");
            }

            // Query base para programaciones del usuario
            $query = Programacion::with([
                'asignacion.usuario',
                'asignacion.ministerio', 
                'asignacion.rol',
                'actividad.ministerio'
            ])
            ->whereIn('id_asignacion', $asignacionesUsuario);

            // Aplicar filtros
            if ($request->has('ministerio') && $request->ministerio) {
                $query->whereHas('actividad.ministerio', function($q) use ($request) {
                    $q->where('id_ministerio', $request->ministerio);
                });
            }

            if ($request->has('estado') && $request->estado) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('fecha') && $request->fecha) {
                $query->where('fecha', $request->fecha);
            }

            $programaciones = $query->orderBy('fecha', 'desc')
                ->orderBy('hora_inicio')
                ->get();

            \Log::info("=== PROGRAMACIONES FINALES PARA EL USUARIO ===");
            \Log::info("Total programaciones encontradas: " . $programaciones->count());
            foreach($programaciones as $prog) {
                \Log::info("Programación ID: {$prog->id_programacion} | Usuario: {$prog->asignacion->usuario->nombre} | Ministerio: {$prog->actividad->ministerio->nombre_ministerio} | Fecha: {$prog->fecha}");
            }

            $ministerios = Ministerio::all();
            $servidores = Usuario::where('activo', true)
                                ->where('id_usuario', '!=', $userId)
                                ->get();

            return view('asistencia.index', compact('programaciones', 'ministerios', 'servidores'));

        } catch (\Exception $e) {
            \Log::error('Error en AsignacionesController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $programaciones = collect();
            $ministerios = Ministerio::all();
            $servidores = collect();
            
            return view('asistencia.index', compact('programaciones', 'ministerios', 'servidores'))
                ->with('error', 'Error al cargar las asignaciones');
        }
    }
    /**
     * ✅ CONFIRMAR ASISTENCIA (AJAX)
     */
    public function confirmar($id)
    {
        try {
            $userId = Auth::id();
            
            $programacion = Programacion::with(['asignacion', 'actividad'])
                ->whereHas('asignacion', function($query) use ($userId) {
                    $query->where('id_usuario', $userId);
                })
                ->findOrFail($id);
                
            $programacion->update([
                'estado' => 'Confirmado',
                'confirmado' => true
            ]);
            
            \Log::info("Asistencia confirmada - Programación ID: " . $id . " por Usuario ID: " . $userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Asistencia confirmada exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en AsignacionesController@confirmar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ SOLICITAR REEMPLAZO (AJAX)
     */
    public function solicitarReemplazo(Request $request, $id)
    {
        try {
            $request->validate([
                'motivo' => 'required|string|max:200',
                'id_usuario_reemplazo' => 'nullable|exists:usuarios,id_usuario'
            ]);

            $userId = Auth::id();
            
            DB::transaction(function () use ($id, $request, $userId) {
                $programacion = Programacion::with('asignacion')
                    ->whereHas('asignacion', function($query) use ($userId) {
                        $query->where('id_usuario', $userId);
                    })
                    ->findOrFail($id);

                // Crear reemplazo
                Reemplazo::create([
                    'id_programacion' => $id,
                    'id_asignacion_reemplazado' => $programacion->id_asignacion,
                    'id_asignacion_reemplazo_por' => $request->id_usuario_reemplazo,
                    'motivo' => $request->motivo,
                    'estado' => 'Pendiente',
                    'fecha_solicitud' => now()
                ]);

                // Actualizar estado de la programación
                $programacion->update([
                    'estado' => 'Reemplazado',
                    'confirmado' => false
                ]);
                
                \Log::info("Reemplazo solicitado - Programación ID: " . $id . " por Usuario ID: " . $userId);
            });

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de reemplazo enviada exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en AsignacionesController@solicitarReemplazo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al solicitar reemplazo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ OBTENER DETALLES DE PROGRAMACIÓN (AJAX)
     */
    public function show($id)
    {
        try {
            $userId = Auth::id();
            
            $programacion = Programacion::with([
                'asignacion.usuario',
                'asignacion.rol',
                'actividad.ministerio'
            ])
            ->whereHas('asignacion', function($query) use ($userId) {
                $query->where('id_usuario', $userId);
            })
            ->findOrFail($id);

            return response()->json($programacion);

        } catch (\Exception $e) {
            \Log::error('Error en AsignacionesController@show: ' . $e->getMessage());
            return response()->json([
                'error' => 'Programación no encontrada'
            ], 404);
        }
    }
}