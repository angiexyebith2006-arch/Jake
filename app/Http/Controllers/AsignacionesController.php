<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programacion;
use App\Models\Reemplazo;
use App\Models\Autorizacione;
use App\Models\Asignacion;
use App\Models\Ministerio;
use App\Models\Usuario;
use App\Services\JavaApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AsignacionesController extends Controller
{
    protected $javaApi;
    
    public function __construct(JavaApiService $javaApi)
    {
        $this->javaApi = $javaApi;
    }

    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $usandoApiJava = env('JAVA_API_BASE_URL') ? true : false;
            
            Log::info("=== ASIGNACIONES CONTROLLER ===");
            Log::info("Usuario ID: " . $userId);
            Log::info("Usando API Java: " . ($usandoApiJava ? 'Sí' : 'No'));

            if ($usandoApiJava) {
                // ========== USANDO API JAVA ==========
                Log::info("Obteniendo datos desde API Java...");
                
                // Preparar filtros
                $filters = [];
                
                if ($request->has('ministerio') && $request->ministerio) {
                    $filters['ministerio'] = $request->ministerio;
                }

                if ($request->has('estado') && $request->estado) {
                    $filters['estado'] = $request->estado;
                }

                if ($request->has('fecha') && $request->fecha) {
                    $filters['fecha'] = $request->fecha;
                }

                // 1. Obtener programaciones desde API Java
                $programacionesResponse = $this->javaApi->getProgramacionesUsuario($userId, $filters);
                
                if ($programacionesResponse['success']) {
                    $programacionesApi = collect($programacionesResponse['data'] ?? []);
                    Log::info("Programaciones desde API Java: " . $programacionesApi->count());
                    
                    // Convertir a colección de objetos para compatibilidad con la vista
                    $programaciones = $programacionesApi->map(function ($item) {
                        return (object) [
                            'id_programacion' => $item['id_programacion'] ?? null,
                            'fecha' => $item['fecha'] ?? null,
                            'hora_inicio' => $item['hora_inicio'] ?? null,
                            'hora_fin' => $item['hora_fin'] ?? null,
                            'estado' => $item['estado'] ?? 'Pendiente',
                            'confirmado' => $item['confirmado'] ?? false,
                            'asignacion' => (object) [
                                'usuario' => (object) [
                                    'id_usuario' => $item['asignacion']['usuario']['id_usuario'] ?? null,
                                    'nombre' => $item['asignacion']['usuario']['nombre'] ?? 'Sin nombre'
                                ],
                                'rol' => (object) [
                                    'nombre_rol' => $item['asignacion']['rol']['nombre_rol'] ?? 'Sin rol'
                                ]
                            ],
                            'actividad' => (object) [
                                'nombre_actividad' => $item['actividad']['nombre_actividad'] ?? 'Sin actividad',
                                'ministerio' => (object) [
                                    'nombre_ministerio' => $item['actividad']['ministerio']['nombre_ministerio'] ?? 'Sin ministerio'
                                ]
                            ]
                        ];
                    });
                } else {
                    Log::error("Error API Java: " . ($programacionesResponse['error'] ?? 'Desconocido'));
                    $programaciones = collect();
                }

                // 2. Obtener ministerios desde API Java
                $ministeriosResponse = $this->javaApi->getMinisterios();
                if ($ministeriosResponse['success']) {
                    $ministerios = collect($ministeriosResponse['data'] ?? [])->map(function ($item) {
                        return (object) [
                            'id_ministerio' => $item['id_ministerio'] ?? null,
                            'nombre_ministerio' => $item['nombre_ministerio'] ?? 'Sin nombre'
                        ];
                    });
                } else {
                    // Fallback a base de datos local
                    $ministerios = Ministerio::all();
                }

                // 3. Obtener servidores desde API Java
                $servidoresResponse = $this->javaApi->getServidores($userId);
                if ($servidoresResponse['success']) {
                    $servidores = collect($servidoresResponse['data'] ?? [])->map(function ($item) {
                        return (object) [
                            'id_usuario' => $item['id_usuario'] ?? null,
                            'nombre' => $item['nombre'] ?? 'Sin nombre'
                        ];
                    });
                } else {
                    // Fallback a base de datos local
                    $servidores = Usuario::where('activo', true)
                                        ->where('id_usuario', '!=', $userId)
                                        ->get();
                }

            } else {
                // ========== USANDO BASE DE DATOS LOCAL (tu código original) ==========
                Log::info("Usando base de datos local...");
                
                // Obtener las asignaciones del usuario autenticado
                $asignacionesUsuario = Asignacion::where('id_usuario', $userId)
                    ->where('activo', true)
                    ->pluck('id_asignacion');

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

                $ministerios = Ministerio::all();
                $servidores = Usuario::where('activo', true)
                                    ->where('id_usuario', '!=', $userId)
                                    ->get();
            }

            // DEBUG: Mostrar datos obtenidos
            Log::info("Programaciones finales: " . $programaciones->count());
            Log::info("Ministerios: " . $ministerios->count());
            Log::info("Servidores: " . $servidores->count());

            return view('asistencia.index', compact('programaciones', 'ministerios', 'servidores'));

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // En caso de error, retornar datos vacíos
            return view('asistencia.index', [
                'programaciones' => collect(),
                'ministerios' => collect(),
                'servidores' => collect()
            ])->with('error', 'Error al cargar las asignaciones: ' . $e->getMessage());
        }
    }

    /**
     * ✅ CONFIRMAR ASISTENCIA (AJAX)
     */
    public function confirmar($id)
    {
        try {
            $userId = Auth::id();
            $usandoApiJava = env('JAVA_API_BASE_URL') ? true : false;

            if ($usandoApiJava) {
                // Usar API Java
                $response = $this->javaApi->confirmarAsistencia($id, $userId);
                
                if ($response['success']) {
                    Log::info("Asistencia confirmada en API Java - Programación ID: " . $id);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Asistencia confirmada exitosamente',
                        'data' => $response['data']
                    ]);
                } else {
                    throw new \Exception($response['error'] ?? 'Error desconocido al confirmar');
                }
            } else {
                // Usar base de datos local (tu código original)
                $programacion = Programacion::with(['asignacion', 'actividad'])
                    ->whereHas('asignacion', function($query) use ($userId) {
                        $query->where('id_usuario', $userId);
                    })
                    ->findOrFail($id);
                    
                $programacion->update([
                    'estado' => 'Confirmado',
                    'confirmado' => true
                ]);
                
                Log::info("Asistencia confirmada local - Programación ID: " . $id . " por Usuario ID: " . $userId);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Asistencia confirmada exitosamente'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@confirmar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ SOLICITAR REEMPLAZO (AJAX) - CORREGIDO
     */
   public function solicitarReemplazo(Request $request, $id)
{
    try {
        DB::beginTransaction();

        // Validar la solicitud
        $request->validate([
            'motivo' => 'required|string|min:5',
            'id_usuario_reemplazo' => 'nullable|exists:usuarios,id_usuario'
        ]);

        $userId = Auth::id();
        $usandoApiJava = env('JAVA_API_BASE_URL') ? true : false;

        if ($usandoApiJava) {
            // ... código de API Java ...
        }

        // ========== LÓGICA LOCAL ==========
        
        // 1. Buscar la programación
        $programacion = Programacion::with(['asignacion', 'asignacion.usuario', 'actividad'])
            ->findOrFail($id);

        Log::info("Programación encontrada: " . $programacion->id_programacion);

        // 2. Verificar que el usuario autenticado es el asignado
        if ($programacion->asignacion->id_usuario != $userId) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para solicitar reemplazo en esta programación.'
            ], 403);
        }

        // 3. Crear el reemplazo
        $asignacionReemplazo = null;
        
        if ($request->id_usuario_reemplazo) {
            // Buscar la asignación del usuario sugerido
            $asignacionReemplazo = Asignacion::where('id_usuario', $request->id_usuario_reemplazo)
                ->where('id_ministerio', $programacion->asignacion->id_ministerio)
                ->where('id_rol', $programacion->asignacion->id_rol)
                ->where('activo', true)
                ->first();
        }
        
        // Si no se encontró asignación específica
        if (!$asignacionReemplazo) {
            $asignacionReemplazo = Asignacion::where('id_ministerio', $programacion->asignacion->id_ministerio)
                ->where('activo', true)
                ->where('id_usuario', '!=', $userId)
                ->where('id_asignacion', '!=', $programacion->id_asignacion)
                ->first();
        }
        
        if (!$asignacionReemplazo) {
            $asignacionReemplazo = Asignacion::where('activo', true)
                ->where('id_usuario', '!=', $userId)
                ->first();
            
            if (!$asignacionReemplazo) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un servidor disponible para reemplazo.'
                ], 400);
            }
        }
        
        // Crear el reemplazo
        $reemplazo = Reemplazo::create([
            'id_programacion' => $id,
            'id_asignacion_reemplazado' => $programacion->id_asignacion,
            'id_asignacion_reemplazo_por' => $asignacionReemplazo->id_asignacion,
            'motivo' => $request->motivo,
            'fecha_solicitud' => Carbon::now()->toDateString(),
            'estado' => 'Pendiente'
        ]);
        
        Log::info("Reemplazo creado con ID: " . $reemplazo->id_reemplazo);

        // 4. NO verificar autorización existente (la tabla no tiene estado)
        // En lugar de verificar, simplemente crear la autorización
        
        // 5. Obtener autorizador por defecto
        $idAutorizador = $this->obtenerAutorizadorPorDefecto();
        Log::info("Autorizador asignado: " . $idAutorizador);

        // 6. Crear la autorización según la estructura REAL de tu tabla
        $autorizacion = Autorizacione::create([
            'id_reemplazo' => $reemplazo->id_reemplazo,
            'id_autorizador' => $idAutorizador,
            'fecha_autorizacion' => null, // Pendiente de aprobación
            'observaciones' => $request->motivo // Usar el motivo como observaciones
        ]);

        Log::info("Autorización creada con ID: " . $autorizacion->id_autorizacion);

        // 7. Actualizar estado de la programación
        $programacion->update(['estado' => 'Reemplazado']);
        
        Log::info("Estado de programación actualizado a: Reemplazado");

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud de reemplazo enviada correctamente.',
            'reemplazo_id' => $reemplazo->id_reemplazo,
            'autorizacion_id' => $autorizacion->id_autorizacion,
            'programacion_estado' => 'Reemplazado'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        Log::error('Error de validación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->getErrors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error en solicitarReemplazo: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ], 500);
    }
}
    public function obtenerAutorizadorPorDefecto()
    {
        try {
            Log::info("Buscando autorizador por defecto...");
            
            // Buscar usuario con rol específico de autorizador (lider, coordinador, admin)
            $autorizador = DB::table('usuarios')
                ->join('asignaciones', 'usuarios.id_usuario', '=', 'asignaciones.id_usuario')
                ->join('roles', 'asignaciones.id_rol', '=', 'roles.id_rol')
                ->where(function($query) {
                    $query->where('roles.nombre_rol', 'like', '%admin%')
                          ->orWhere('roles.nombre_rol', 'like', '%Admin%')
                          ->orWhere('roles.nombre_rol', 'like', '%lider%')
                          ->orWhere('roles.nombre_rol', 'like', '%Líder%')
                          ->orWhere('roles.nombre_rol', 'like', '%coordinador%')
                          ->orWhere('roles.nombre_rol', 'like', '%Coordinador%');
                })
                ->where('usuarios.activo', true)
                ->where('asignaciones.activo', true)
                ->orderBy('usuarios.id_usuario', 'asc')
                ->select('usuarios.id_usuario')
                ->first();

            if ($autorizador) {
                Log::info("Autorizador encontrado: " . $autorizador->id_usuario);
                return $autorizador->id_usuario;
            }

            // Opción 2: Buscar cualquier usuario activo (fallback)
            $usuarioDefault = Usuario::where('activo', true)
                ->orderBy('id_usuario', 'asc')
                ->first();

            if ($usuarioDefault) {
                Log::warning("Usando usuario por defecto (fallback): " . $usuarioDefault->id_usuario);
                return $usuarioDefault->id_usuario;
            }

            // Opción 3: Usar el usuario autenticado si existe
            if (Auth::check()) {
                Log::warning("Usando usuario autenticado como autorizador: " . Auth::id());
                return Auth::id();
            }

            // Opción 4: Valor fijo como último recurso
            Log::error("No se encontró autorizador, usando ID 1 como fallback final");
            return 1;

        } catch (\Exception $e) {
            Log::error('Error en obtenerAutorizadorPorDefecto: ' . $e->getMessage());
            return 1; // Valor por defecto en caso de error
        }
    }

    /**
     * Obtener detalles de una programación para el modal
     */
    public function getProgramacion($id)
    {
        $programacion = Programacion::with([
            'asignacion.usuario',
            'asignacion.rol',
            'actividad.ministerio'
        ])->findOrFail($id);
        
        return response()->json($programacion);
    }

    /**
     * ✅ OBTENER DETALLES DE PROGRAMACIÓN (AJAX)
     */
    public function show($id)
    {
        try {
            $userId = Auth::id();
            $usandoApiJava = env('JAVA_API_BASE_URL') ? true : false;

            if ($usandoApiJava) {
                // Usar API Java
                $response = $this->javaApi->getProgramacion($id);
                
                if ($response['success']) {
                    return response()->json($response['data']);
                } else {
                    throw new \Exception($response['error'] ?? 'Programación no encontrada');
                }
            } else {
                // Usar base de datos local (tu código original)
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
            }

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@show: ' . $e->getMessage());
            return response()->json([
                'error' => 'Programación no encontrada'
            ], 404);
        }
    }

    /**
     * ✅ OBTENER USUARIOS DISPONIBLES PARA REEMPLAZO (AJAX)
     */
    public function getUsuariosReemplazo($id)
    {
        try {
            $userId = Auth::id();
            
            // Obtener la programación
            $programacion = Programacion::with('asignacion')
                ->whereHas('asignacion', function($query) use ($userId) {
                    $query->where('id_usuario', $userId);
                })
                ->findOrFail($id);

            // Obtener la asignación actual
            $asignacionActual = $programacion->asignacion;
            
            // Buscar usuarios con la misma asignación (mismo ministerio y rol)
            $usuariosReemplazo = Asignacion::with('usuario')
                ->where('id_ministerio', $asignacionActual->id_ministerio)
                ->where('id_rol', $asignacionActual->id_rol)
                ->where('activo', true)
                ->where('id_usuario', '!=', $userId)
                ->get()
                ->map(function ($asignacion) {
                    return [
                        'id_usuario' => $asignacion->usuario->id_usuario,
                        'id_asignacion' => $asignacion->id_asignacion,
                        'nombre' => $asignacion->usuario->nombre,
                        'email' => $asignacion->usuario->correo,
                        'telefono' => $asignacion->usuario->telefono
                    ];
                });

            // Si no hay usuarios con el mismo rol, buscar cualquier usuario del mismo ministerio
            if ($usuariosReemplazo->isEmpty()) {
                $usuariosReemplazo = Asignacion::with('usuario')
                    ->where('id_ministerio', $asignacionActual->id_ministerio)
                    ->where('activo', true)
                    ->where('id_usuario', '!=', $userId)
                    ->get()
                    ->map(function ($asignacion) {
                        return [
                            'id_usuario' => $asignacion->usuario->id_usuario,
                            'id_asignacion' => $asignacion->id_asignacion,
                            'nombre' => $asignacion->usuario->nombre,
                            'rol' => $asignacion->rol->nombre_rol,
                            'email' => $asignacion->usuario->correo
                        ];
                    });
            }

            return response()->json([
                'success' => true,
                'usuarios' => $usuariosReemplazo,
                'info' => [
                    'ministerio' => $asignacionActual->ministerio->nombre_ministerio ?? 'Sin ministerio',
                    'rol' => $asignacionActual->rol->nombre_rol ?? 'Sin rol'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en getUsuariosReemplazo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios para reemplazo'
            ], 500);
        }
    }

    /**
     * NUEVO: Endpoint para verificar estado de API Java
     */
    public function verificarApi()
    {
        try {
            if (!env('JAVA_API_BASE_URL')) {
                return response()->json([
                    'status' => 'local',
                    'message' => 'Usando base de datos local'
                ]);
            }
            
            // Probar conexión simple
            $response = $this->javaApi->makeRequest('GET', 'health');
            
            return response()->json([
                'status' => $response['success'] ? 'connected' : 'error',
                'message' => $response['success'] ? 'API Java conectada' : 'Error en API Java',
                'details' => $response
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al verificar API: ' . $e->getMessage()
            ], 500);
        }
    }
}