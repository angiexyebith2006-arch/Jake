<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AsignacionesController extends Controller
{
    protected $javaApiService;
    protected $apiUrl = 'http://localhost:5431/api/asignaciones';

    public function __construct(JavaApiService $javaApiService)
    {
        $this->javaApiService = $javaApiService;
    }

    /**
     * ======================================================
     * LISTAR PROGRAMACIONES DEL USUARIO (ASISTENCIA)
     * ======================================================
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Debes iniciar sesión para acceder a esta sección');
            }

            // =============================
            // OBTENER FILTROS DE LA REQUEST
            // =============================
            $filters = $this->prepareFilters($request);

            // =============================
            // OBTENER PROGRAMACIONES DEL USUARIO
            // =============================
            $programacionesData = $this->getUserProgramaciones($userId, $filters);
            $programaciones = $programacionesData['programaciones'] ?? collect();
            $stats = $programacionesData['stats'] ?? [];

            // =============================
            // OBTENER DATOS PARA LOS FILTROS
            // =============================
            $filterData = $this->getFilterData();

            // =============================
            // RETORNAR VISTA
            // =============================
            return view('asistencia.index', [
                'programaciones' => $programaciones,
                'actividades' => $filterData['actividades'],
                'ministerios' => $filterData['ministerios'],
                'servidores' => $filterData['servidores'],
                'stats' => $stats,
                'filters' => $filters
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return view('asistencia.index', [
                'programaciones' => collect(),
                'actividades' => collect(),
                'ministerios' => collect(),
                'servidores' => collect(),
                'stats' => [],
                'error' => 'Error al cargar las asignaciones: ' . $e->getMessage()
            ]);
        }
    }

// En tu AsignacionesController.php, agrega estos métodos:

/**
 * ======================================================
 * OBTENER ASIGNACIONES (NUEVO MÉTODO)
 * ======================================================
 */
public function getAsignaciones(Request $request)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Obtener filtros
        $filters = [];
        
        if ($request->has('ministerio') && $request->ministerio) {
            $filters['ministerio'] = $request->ministerio;
        }
        
        if ($request->has('activo') && $request->activo !== null) {
            $filters['activo'] = $request->activo === 'true';
        }
        
        if ($request->has('usuario') && $request->usuario) {
            $filters['usuario'] = $request->usuario;
        }

        // Obtener asignaciones desde API Java
        $response = $this->javaApiService->getAsignaciones($filters);
        
        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al obtener asignaciones',
                'data' => []
            ], 500);
        }

        // Procesar datos recibidos
        $asignaciones = $this->procesarAsignaciones($response['data'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $asignaciones,
            'total' => count($asignaciones)
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@getAsignaciones: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener asignaciones: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}

/**
 * ======================================================
 * OBTENER ASIGNACIONES DEL USUARIO LOGUEADO
 * ======================================================
 */
public function getMisAsignaciones(Request $request)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Obtener asignaciones del usuario desde API Java
        $response = $this->javaApiService->getAsignacionesPorUsuario($userId);
        
        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al obtener tus asignaciones',
                'data' => []
            ], 500);
        }

        // Procesar datos recibidos
        $asignaciones = $this->procesarAsignaciones($response['data'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $asignaciones,
            'total' => count($asignaciones)
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@getMisAsignaciones: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener tus asignaciones: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}

/**
 * ======================================================
 * OBTENER ASIGNACIONES ACTIVAS
 * ======================================================
 */
public function getAsignacionesActivas(Request $request)
{
    try {
        // Obtener filtros
        $filters = ['activo' => true];
        
        if ($request->has('ministerio') && $request->ministerio) {
            $filters['ministerio'] = $request->ministerio;
        }
        
        if ($request->has('usuario') && $request->usuario) {
            $filters['usuario'] = $request->usuario;
        }

        // Obtener asignaciones activas desde API Java
        $response = $this->javaApiService->getAsignaciones($filters);
        
        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al obtener asignaciones activas',
                'data' => []
            ], 500);
        }

        // Procesar datos recibidos
        $asignaciones = $this->procesarAsignaciones($response['data'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $asignaciones,
            'total' => count($asignaciones)
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@getAsignacionesActivas: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener asignaciones activas: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}

/**
 * ======================================================
 * OBTENER UNA ASIGNACIÓN POR ID
 * ======================================================
 */
public function getAsignacion($id)
{
    try {
        // Obtener asignación específica desde API Java
        $response = $this->javaApiService->getAsignacion($id);
        
        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Asignación no encontrada',
                'data' => null
            ], 404);
        }

        // Procesar datos recibidos
        $asignacion = $this->procesarAsignacion($response['data'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $asignacion
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@getAsignacion: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener la asignación',
            'data' => null
        ], 500);
    }
}

/**
 * ======================================================
 * PROCESAR LISTA DE ASIGNACIONES
 * ======================================================
 */
private function procesarAsignaciones(array $datos): array
{
    return collect($datos)->map(function ($item) {
        return $this->mapearDatosAsignacion($item);
    })->toArray();
}

/**
 * ======================================================
 * PROCESAR UNA ASIGNACIÓN
 * ======================================================
 */
private function procesarAsignacion(array $dato): array
{
    return $this->mapearDatosAsignacion($dato);
}

/**
 * ======================================================
 * MAPEAR DATOS DE ASIGNACIÓN (Para el JSON que muestras)
 * ======================================================
 */
private function mapearDatosAsignacion(array $item): array
{
    // Extraer datos de manera segura según la estructura que muestras
    return [
        'id' => $item['id'] ?? $item['id_asignacion'] ?? $item['idAsignacion'] ?? null,
        'fechaAsignacion' => $item['fechaAsignacion'] ?? $item['fecha_asignacion'] ?? null,
        'nombreUsuario' => $item['nombreUsuario'] ?? $item['nombre_usuario'] ?? $item['usuario']['nombre'] ?? 'Sin nombre',
        'nombreRol' => $item['nombreRol'] ?? $item['nombre_rol'] ?? $item['rol']['nombre_rol'] ?? $item['rol']['nombreRol'] ?? 'Sin rol',
        'activo' => $item['activo'] ?? true,
        
        // Datos adicionales que podrían estar disponibles
        'id_usuario' => $item['id_usuario'] ?? $item['idUsuario'] ?? $item['usuario']['id'] ?? $item['usuario']['id_usuario'] ?? null,
        'id_rol' => $item['id_rol'] ?? $item['idRol'] ?? $item['rol']['id'] ?? $item['rol']['id_rol'] ?? null,
        'id_ministerio' => $item['id_ministerio'] ?? $item['idMinisterio'] ?? $item['ministerio']['id'] ?? $item['ministerio']['id_ministerio'] ?? null,
        'nombreMinisterio' => $item['nombreMinisterio'] ?? $item['nombre_ministerio'] ?? $item['ministerio']['nombre_ministerio'] ?? $item['ministerio']['nombreMinisterio'] ?? null,
        
        // Información del usuario
        'usuario' => [
            'id' => $item['usuario']['id'] ?? $item['usuario']['id_usuario'] ?? $item['usuario']['idUsuario'] ?? null,
            'nombre' => $item['usuario']['nombre'] ?? 'Sin nombre',
            'correo' => $item['usuario']['correo'] ?? $item['usuario']['email'] ?? null,
            'telefono' => $item['usuario']['telefono'] ?? null,
            'activo' => $item['usuario']['activo'] ?? true
        ],
        
        // Información del rol
        'rol' => [
            'id' => $item['rol']['id'] ?? $item['rol']['id_rol'] ?? $item['rol']['idRol'] ?? null,
            'nombre' => $item['rol']['nombre'] ?? $item['rol']['nombre_rol'] ?? $item['rol']['nombreRol'] ?? 'Sin rol',
            'descripcion' => $item['rol']['descripcion'] ?? null
        ],
        
        // Información del ministerio
        'ministerio' => [
            'id' => $item['ministerio']['id'] ?? $item['ministerio']['id_ministerio'] ?? $item['ministerio']['idMinisterio'] ?? null,
            'nombre' => $item['ministerio']['nombre'] ?? $item['ministerio']['nombre_ministerio'] ?? $item['ministerio']['nombreMinisterio'] ?? 'Sin ministerio',
            'descripcion' => $item['ministerio']['descripcion'] ?? null
        ]
    ];
}

/**
 * ======================================================
 * CREAR NUEVA ASIGNACIÓN
 * ======================================================
 */
public function crearAsignacion(Request $request)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Validar los datos
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'required|integer|min:1',
            'id_ministerio' => 'required|integer|min:1',
            'id_rol' => 'required|integer|min:1',
            'fecha_asignacion' => 'nullable|date',
            'activo' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Preparar datos para enviar a API Java
        $data = [
            'idUsuario' => $request->id_usuario,
            'idMinisterio' => $request->id_ministerio,
            'idRol' => $request->id_rol,
            'fechaAsignacion' => $request->fecha_asignacion ?? date('Y-m-d'),
            'activo' => $request->activo ?? true
        ];

        // Enviar solicitud a API Java
        $response = $this->javaApiService->createAsignacion($data);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al crear la asignación'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asignación creada exitosamente',
            'data' => $this->mapearDatosAsignacion($response['data'] ?? [])
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@crearAsignacion: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al crear la asignación: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ======================================================
 * ACTUALIZAR ASIGNACIÓN
 * ======================================================
 */
public function actualizarAsignacion(Request $request, $id)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Validar los datos
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'nullable|integer|min:1',
            'id_ministerio' => 'nullable|integer|min:1',
            'id_rol' => 'nullable|integer|min:1',
            'fecha_asignacion' => 'nullable|date',
            'activo' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Preparar datos para enviar a API Java
        $data = [];
        
        if ($request->has('id_usuario')) {
            $data['idUsuario'] = $request->id_usuario;
        }
        
        if ($request->has('id_ministerio')) {
            $data['idMinisterio'] = $request->id_ministerio;
        }
        
        if ($request->has('id_rol')) {
            $data['idRol'] = $request->id_rol;
        }
        
        if ($request->has('fecha_asignacion')) {
            $data['fechaAsignacion'] = $request->fecha_asignacion;
        }
        
        if ($request->has('activo')) {
            $data['activo'] = $request->activo;
        }

        // Enviar solicitud a API Java
        $response = $this->javaApiService->updateAsignacion($id, $data);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al actualizar la asignación'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asignación actualizada exitosamente',
            'data' => $this->mapearDatosAsignacion($response['data'] ?? [])
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@actualizarAsignacion: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la asignación: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ======================================================
 * ELIMINAR/INACTIVAR ASIGNACIÓN
 * ======================================================
 */
public function eliminarAsignacion($id)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Enviar solicitud a API Java para inactivar
        $response = $this->javaApiService->deleteAsignacion($id);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al eliminar la asignación'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asignación inactivada exitosamente',
            'data' => $response['data'] ?? null
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@eliminarAsignacion: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la asignación: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ======================================================
 * ACTIVAR ASIGNACIÓN
 * ======================================================
 */
public function activarAsignacion($id)
{
    try {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Enviar solicitud a API Java para activar
        $response = $this->javaApiService->activarAsignacion($id);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Error al activar la asignación'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asignación activada exitosamente',
            'data' => $response['data'] ?? null
        ]);

    } catch (\Exception $e) {
        Log::error('Error en AsignacionesController@activarAsignacion: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
                'message' => 'Error al activar la asignación: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * ======================================================
     * PREPARAR FILTROS
     * ======================================================
     */
    private function prepareFilters(Request $request): array
    {
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

        if ($request->has('actividad') && $request->actividad) {
            $filters['actividad'] = $request->actividad;
        }

        return $filters;
    }

    /**
     * ======================================================
     * OBTENER PROGRAMACIONES DEL USUARIO
     * ======================================================
     */
    private function getUserProgramaciones($userId, array $filters = []): array
    {
        $respuesta = $this->javaApiService->getProgramacionesUsuario($userId, $filters);
        
        if (!$respuesta['success']) {
            Log::warning('No se pudieron obtener programaciones para el usuario: ' . $userId);
            return [
                'programaciones' => collect(),
                'stats' => $this->getDefaultStats()
            ];
        }

        $lista = $respuesta['data'] ?? [];
        
        // Convertir a colección estructurada
        $programaciones = collect($lista)->map(function ($item) {
            return $this->mapProgramacionData($item);
        });

        // Calcular estadísticas
        $stats = $this->calculateStats($programaciones);

        return [
            'programaciones' => $programaciones,
            'stats' => $stats
        ];
    }

    /**
     * ======================================================
     * MAPEAR DATOS DE PROGRAMACIÓN
     * ======================================================
     */
    private function mapProgramacionData(array $item): object
    {
        // Extraer datos de manera segura
        $idProgramacion = $item['idProgramacion'] ?? $item['id_programacion'] ?? null;
        $estado = $item['estado'] ?? 'Pendiente';
        $confirmado = $item['confirmado'] ?? false;
        $fecha = $item['fecha'] ?? null;
        $horaInicio = $item['horaInicio'] ?? $item['hora_inicio'] ?? null;
        $horaFin = $item['horaFin'] ?? $item['hora_fin'] ?? null;

        // Extraer datos del usuario
        $usuarioData = $item['usuario'] ?? $item['asignacion']['usuario'] ?? [];
        $usuarioNombre = $usuarioData['nombre'] ?? 'Sin nombre';
        $usuarioId = $usuarioData['idUsuario'] ?? $usuarioData['id_usuario'] ?? null;

        // Extraer datos del rol
        $rolData = $item['rol'] ?? $item['asignacion']['rol'] ?? [];
        $rolNombre = $rolData['nombreRol'] ?? $rolData['nombre_rol'] ?? 'Sin rol';

        // Extraer datos de la actividad
        $actividadData = $item['actividad'] ?? [];
        $actividadNombre = $actividadData['nombreActividad'] ?? $actividadData['nombre_actividad'] ?? 'Sin actividad';
        $actividadId = $actividadData['idActividad'] ?? $actividadData['id_actividad'] ?? null;

        // Extraer datos del ministerio
        $ministerioData = $item['ministerio'] ?? $actividadData['ministerio'] ?? [];
        $ministerioNombre = $ministerioData['nombreMinisterio'] ?? $ministerioData['nombre_ministerio'] ?? 'Sin ministerio';

        return (object) [
            'id_programacion' => $idProgramacion,
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'estado' => $estado,
            'confirmado' => (bool) $confirmado,
            'asignacion' => (object) [
                'usuario' => (object) [
                    'id_usuario' => $usuarioId,
                    'nombre' => $usuarioNombre
                ],
                'rol' => (object) [
                    'nombre_rol' => $rolNombre
                ]
            ],
            'actividad' => (object) [
                'id_actividad' => $actividadId,
                'nombre_actividad' => $actividadNombre,
                'ministerio' => (object) [
                    'nombre_ministerio' => $ministerioNombre
                ]
            ],
        ];
    }

    /**
     * ======================================================
     * CALCULAR ESTADÍSTICAS
     * ======================================================
     */
    private function calculateStats($programaciones): array
    {
        return [
            'total' => $programaciones->count(),
            'confirmadas' => $programaciones->where('estado', 'Confirmado')->count(),
            'pendientes' => $programaciones->where('estado', 'Pendiente')->count(),
            'reemplazadas' => $programaciones->where('estado', 'Reemplazado')->count(),
            'hoy' => $programaciones->where('fecha', date('Y-m-d'))->count(),
        ];
    }

    /**
     * ======================================================
     * ESTADÍSTICAS POR DEFECTO
     * ======================================================
     */
    private function getDefaultStats(): array
    {
        return [
            'total' => 0,
            'confirmadas' => 0,
            'pendientes' => 0,
            'reemplazadas' => 0,
            'hoy' => 0,
        ];
    }

    /**
     * ======================================================
     * OBTENER DATOS PARA FILTROS
     * ======================================================
     */
    private function getFilterData(): array
    {
        $actividades = collect();
        $ministerios = collect();
        $servidores = collect();

        try {
            // Obtener actividades
            $actividadesRespuesta = $this->javaApiService->getActividades();
            if ($actividadesRespuesta['success']) {
                $actividades = collect($actividadesRespuesta['data'] ?? [])->map(function ($item) {
                    return (object) [
                        'id_actividad' => $item['idActividad'] ?? $item['id_actividad'] ?? null,
                        'nombre_actividad' => $item['nombreActividad'] ?? $item['nombre_actividad'] ?? 'Sin nombre',
                        'ministerio' => $item['ministerio']['nombreMinisterio'] ?? $item['ministerio']['nombre_ministerio'] ?? null
                    ];
                });
            }

            // Obtener ministerios
            $ministeriosRespuesta = $this->javaApiService->getMinisterios();
            if ($ministeriosRespuesta['success']) {
                $ministerios = collect($ministeriosRespuesta['data'] ?? [])->map(function ($item) {
                    return (object) [
                        'id_ministerio' => $item['idMinisterio'] ?? $item['id_ministerio'] ?? null,
                        'nombre_ministerio' => $item['nombreMinisterio'] ?? $item['nombre_ministerio'] ?? 'Sin nombre'
                    ];
                });
            }

            // Obtener servidores activos
            $servidoresRespuesta = $this->javaApiService->getUsuariosActivos();
            if ($servidoresRespuesta['success']) {
                $servidores = collect($servidoresRespuesta['data'] ?? [])->map(function ($item) {
                    return (object) [
                        'id_usuario' => $item['idUsuario'] ?? $item['id_usuario'] ?? null,
                        'nombre' => $item['nombre'] ?? 'Sin nombre',
                        'correo' => $item['correo'] ?? $item['email'] ?? null
                    ];
                });
            }

        } catch (\Exception $e) {
            Log::error('Error obteniendo datos de filtro: ' . $e->getMessage());
        }

        return [
            'actividades' => $actividades,
            'ministerios' => $ministerios,
            'servidores' => $servidores
        ];
    }

    /**
     * ======================================================
     * CONFIRMAR ASISTENCIA (POST)
     * ======================================================
     */
    public function confirmar(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            
            // Validar que el usuario esté autenticado
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el usuario es el asignado a esta programación
            $programacionRespuesta = $this->javaApiService->getProgramacionUsuario($id, $userId);
            
            if (!$programacionRespuesta['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para confirmar esta programación'
                ], 403);
            }

            // Confirmar la programación
            $response = $this->javaApiService->confirmarProgramacion($id);

            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['error'] ?? 'Error al confirmar asistencia'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asistencia confirmada exitosamente',
                'data' => $response['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@confirmar: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ======================================================
     * SOLICITAR REEMPLAZO
     * ======================================================
     */
    public function solicitarReemplazo(Request $request, $id)
    {
        try {
            // Validar la solicitud
            $validator = Validator::make($request->all(), [
                'motivo' => 'required|string|min:5|max:500',
                'id_usuario_reemplazo' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = Auth::id();

            // Verificar que el usuario esté autenticado
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el usuario es el asignado a esta programación
            $programacionRespuesta = $this->javaApiService->getProgramacionUsuario($id, $userId);
            
            if (!$programacionRespuesta['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para solicitar reemplazo en esta programación'
                ], 403);
            }

            // Verificar que la programación no esté ya confirmada
            $programacion = $programacionRespuesta['data'];
            if (($programacion['confirmado'] ?? false) || ($programacion['estado'] ?? '') === 'Confirmado') {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes solicitar reemplazo en una programación ya confirmada'
                ], 400);
            }

            // Preparar datos para la solicitud
            $data = [
                'idProgramacion' => $id,
                'idUsuarioSolicitante' => $userId,
                'idUsuarioReemplazo' => $request->id_usuario_reemplazo,
                'motivo' => $request->motivo,
                'fechaSolicitud' => now()->toDateString()
            ];

            // Enviar solicitud de reemplazo a API Java
            $response = $this->javaApiService->solicitarReemplazo($data);

            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['error'] ?? 'Error al solicitar reemplazo'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de reemplazo enviada correctamente.',
                'data' => $response['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@solicitarReemplazo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ======================================================
     * OBTENER DETALLES DE PROGRAMACIÓN (Para modal)
     * ======================================================
     */
    public function show($id)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'error' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener programación específica desde API Java
            $response = $this->javaApiService->getProgramacionUsuario($id, $userId);
            
            if (!$response['success']) {
                return response()->json([
                    'error' => $response['error'] ?? 'Programación no encontrada'
                ], 404);
            }

            // Formatear los datos para el modal
            $programacion = $response['data'];
            $formattedData = $this->formatProgramacionForModal($programacion);

            return response()->json($formattedData);

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@show: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error al obtener los detalles de la programación'
            ], 500);
        }
    }

    /**
     * ======================================================
     * FORMATEAR DATOS PARA MODAL
     * ======================================================
     */
    private function formatProgramacionForModal(array $programacion): array
    {
        return [
            'id_programacion' => $programacion['idProgramacion'] ?? $programacion['id_programacion'] ?? null,
            'fecha' => $programacion['fecha'] ?? null,
            'hora_inicio' => $programacion['horaInicio'] ?? $programacion['hora_inicio'] ?? null,
            'hora_fin' => $programacion['horaFin'] ?? $programacion['hora_fin'] ?? null,
            'estado' => $programacion['estado'] ?? 'Pendiente',
            'confirmado' => $programacion['confirmado'] ?? false,
            'asignacion' => [
                'usuario' => [
                    'id_usuario' => $programacion['usuario']['idUsuario'] ?? $programacion['usuario']['id_usuario'] ?? null,
                    'nombre' => $programacion['usuario']['nombre'] ?? 'Sin nombre'
                ],
                'rol' => [
                    'nombre_rol' => $programacion['rol']['nombreRol'] ?? $programacion['rol']['nombre_rol'] ?? 'Sin rol'
                ]
            ],
            'actividad' => [
                'id_actividad' => $programacion['actividad']['idActividad'] ?? $programacion['actividad']['id_actividad'] ?? null,
                'nombre_actividad' => $programacion['actividad']['nombreActividad'] ?? $programacion['actividad']['nombre_actividad'] ?? 'Sin actividad',
            ]
        ];
    }

    /**
     * ======================================================
     * OBTENER USUARIOS DISPONIBLES PARA REEMPLAZO
     * ======================================================
     */
    public function getUsuariosReemplazo($id)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener usuarios para reemplazo desde API Java
            $response = $this->javaApiService->getUsuariosReemplazo($id, $userId);
            
            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['error'] ?? 'Error al obtener usuarios para reemplazo'
                ], 500);
            }

            // Procesar respuesta
            $usuarios = collect($response['data'] ?? [])->map(function ($item) {
                return [
                    'id_usuario' => $item['idUsuario'] ?? $item['id_usuario'] ?? null,
                    'nombre' => $item['nombre'] ?? 'Sin nombre',
                    'email' => $item['email'] ?? $item['correo'] ?? null,
                    'telefono' => $item['telefono'] ?? null,
                    'rol' => $item['rol'] ?? $item['nombreRol'] ?? null,
                    'ministerio' => $item['ministerio'] ?? $item['nombreMinisterio'] ?? null
                ];
            });

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios
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
     * ======================================================
     * CANCELAR CONFIRMACIÓN
     * ======================================================
     */
    public function cancelar($id)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que el usuario es el asignado
            $programacionRespuesta = $this->javaApiService->getProgramacionUsuario($id, $userId);
            
            if (!$programacionRespuesta['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para cancelar esta confirmación'
                ], 403);
            }

            // Cancelar confirmación
            $response = $this->javaApiService->cancelarConfirmacionProgramacion($id);

            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['error'] ?? 'No se pudo cancelar la confirmación'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Confirmación cancelada correctamente',
                'data' => $response['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AsignacionesController@cancelar: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar confirmación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ======================================================
     * VERIFICAR ESTADO DE API JAVA
     * ======================================================
     */
    public function verificarApi()
    {
        try {
            $response = $this->javaApiService->checkHealth();
            
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

    /**
     * ======================================================
     * DEBUG: VER ESTRUCTURA EXACTA DE LA API
     * ======================================================
     */
    public function debugApiEstructura()
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $debugData = [];

            // 1. Test endpoint de programaciones del usuario
            $respuesta = $this->javaApiService->getProgramacionesUsuario($userId, []);
            $debugData['programaciones_usuario'] = $respuesta;

            // 2. Test endpoint de asignaciones del usuario
            $asignacionesResp = $this->javaApiService->getAsignacionesPorUsuario($userId);
            $debugData['asignaciones_usuario'] = $asignacionesResp;

            // 3. Test endpoint de actividades
            $actividadesResp = $this->javaApiService->getActividades();
            $debugData['actividades'] = $actividadesResp;

            // 4. Test endpoint de ministerios
            $ministeriosResp = $this->javaApiService->getMinisterios();
            $debugData['ministerios'] = $ministeriosResp;

            return response()->json([
                'success' => true,
                'user_id' => $userId,
                'debug_data' => $debugData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * ======================================================
     * EXPORTAR ASISTENCIAS A PDF/EXCEL
     * ======================================================
     */
    public function exportar(Request $request, $formato = 'pdf')
    {
        try {
            $userId = Auth::id();
            $filters = $this->prepareFilters($request);
            
            $programacionesData = $this->getUserProgramaciones($userId, $filters);
            $programaciones = $programacionesData['programaciones'];

            if ($programaciones->isEmpty()) {
                return back()->with('error', 'No hay datos para exportar');
            }

            // Aquí iría la lógica de exportación a PDF o Excel
            // Por ahora, solo retornamos un mensaje
            return back()->with('success', 'Funcionalidad de exportación en desarrollo');

        } catch (\Exception $e) {
            Log::error('Error en exportar: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar datos: ' . $e->getMessage());
        }
    }

    /**
     * ======================================================
     * OBTENER ESTADÍSTICAS PARA GRÁFICOS
     * ======================================================
     */
    public function getEstadisticas()
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $programacionesData = $this->getUserProgramaciones($userId);
            $programaciones = $programacionesData['programaciones'];

            // Calcular estadísticas detalladas
            $estadisticas = [
                'por_estado' => [
                    'Pendiente' => $programaciones->where('estado', 'Pendiente')->count(),
                    'Confirmado' => $programaciones->where('estado', 'Confirmado')->count(),
                    'Reemplazado' => $programaciones->where('estado', 'Reemplazado')->count(),
                ],
                'por_mes' => $this->calcularProgramacionesPorMes($programaciones),
                'por_ministerio' => $this->calcularProgramacionesPorMinisterio($programaciones),
                'por_actividad' => $this->calcularProgramacionesPorActividad($programaciones),
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * ======================================================
     * CALCULAR PROGRAMACIONES POR MES
     * ======================================================
     */
    private function calcularProgramacionesPorMes($programaciones): array
    {
        $meses = [
            'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
        ];

        $resultado = array_fill_keys($meses, 0);

        foreach ($programaciones as $programacion) {
            if ($programacion->fecha) {
                try {
                    $mes = date('M', strtotime($programacion->fecha));
                    if (isset($resultado[$mes])) {
                        $resultado[$mes]++;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $resultado;
    }

    /**
     * ======================================================
     * CALCULAR PROGRAMACIONES POR MINISTERIO
     * ======================================================
     */
    private function calcularProgramacionesPorMinisterio($programaciones): array
    {
        $ministerios = [];

        foreach ($programaciones as $programacion) {
            $ministerio = $programacion->actividad->ministerio->nombre_ministerio;
            if (!isset($ministerios[$ministerio])) {
                $ministerios[$ministerio] = 0;
            }
            $ministerios[$ministerio]++;
        }

        return $ministerios;
    }

    /**
     * ======================================================
     * CALCULAR PROGRAMACIONES POR ACTIVIDAD
     * ======================================================
     */
    private function calcularProgramacionesPorActividad($programaciones): array
    {
        $actividades = [];

        foreach ($programaciones as $programacion) {
            $actividad = $programacion->actividad->nombre_actividad;
            if (!isset($actividades[$actividad])) {
                $actividades[$actividad] = 0;
            }
            $actividades[$actividad]++;
        }

        return $actividades;
    }
}