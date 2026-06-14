<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProgramacionController extends Controller
{                               
    protected string $apiUrlProgramaciones = 'http://127.0.0.1:8001/programaciones/api/';
    protected string $apiUrlActividades = 'http://127.0.0.1:8001/actividades/api/actividades';
    protected string $apiUrlAsignaciones = 'http://127.0.0.1:5431/api/asignaciones';

    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')->with('error', 'Por favor, inicie sesión para continuar.');
        }
        return null;
    }

    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function confirmarAsistencia($id_programacion)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . $id_programacion . '/');

            if (!$response->successful()) {
                return redirect()->route('asistencia.index')->with('error', 'No se encontró la programación');
            }

            $programacion = $response->json();

            $updateResponse = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post($this->apiUrlProgramaciones . $id_programacion . '/actualizar/', [
                    'id_actividad'  => $programacion['id_actividad'],
                    'id_asignacion' => $programacion['id_asignacion'],
                    'fecha'         => $programacion['fecha'],
                    'estado'        => 'confirmado'
                ]);

            if ($updateResponse->successful()) {
                return redirect()->route('asistencia.index')->with('success', 'Asistencia confirmada correctamente');
            } else {
                return redirect()->route('asistencia.index')->with('error', 'Error al confirmar asistencia');
            }

        } catch (\Exception $e) {
            return redirect()->route('asistencia.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function mostrarFormularioReemplazo($id_programacion)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $responseProgramacion = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . $id_programacion . '/');

            if (!$responseProgramacion->successful()) {
                return redirect()->route('asistencia.index')->with('error', 'Programación no encontrada');
            }

            $programacion = $responseProgramacion->json();

            $responseAsignacion = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlAsignaciones . '/' . $programacion['id_asignacion']);

            $rolActual = null;
            if ($responseAsignacion->successful()) {
                $asignacion = $responseAsignacion->json();
                $rolActual = $asignacion['cargoNombre'] ?? $asignacion['nombre_rol'] ?? null;
            }

            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlAsignaciones);

            $usuariosReemplazo = [];
            if ($responseAsignaciones->successful()) {
                $asignaciones = $responseAsignaciones->json();
                $asignacionesList = $asignaciones['data'] ?? $asignaciones;
                
                foreach ($asignacionesList as $asignacion) {
                    $rolAsignacion = $asignacion['cargoNombre'] ?? $asignacion['nombre_rol'] ?? null;
                    $idAsignacion  = $asignacion['idAsignacion'] ?? $asignacion['id'] ?? null;
                    
                    if ($rolAsignacion == $rolActual && $idAsignacion != $programacion['id_asignacion']) {
                        $usuariosReemplazo[] = (object)[
                            'id_asignacion'  => $idAsignacion,
                            'nombre_usuario' => $asignacion['usuarioNombre'] ?? $asignacion['nombre_usuario'] ?? 'Usuario',
                            'nombre_rol'     => $rolAsignacion
                        ];
                    }
                }
            }

            return view('asistencia.modal-reemplazo', [
                'programacion'      => (object)$programacion,
                'usuariosReemplazo' => collect($usuariosReemplazo),
                'rol_actual'        => $rolActual
            ]);

        } catch (\Exception $e) {
            Log::error('Error en mostrarFormularioReemplazo', ['error' => $e->getMessage()]);
            return redirect()->route('asistencia.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getUsuariosReemplazo($id_programacion)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $responseProgramacion = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . $id_programacion . '/');

            if (!$responseProgramacion->successful()) {
                return response()->json(['success' => false, 'message' => 'Programación no encontrada']);
            }

            $programacion = $responseProgramacion->json();

            $responseAsignacion = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlAsignaciones . '/' . $programacion['id_asignacion']);

            $rolActual = null;
            if ($responseAsignacion->successful()) {
                $asignacion = $responseAsignacion->json();
                $rolActual  = $asignacion['cargoNombre'] ?? $asignacion['nombre_rol'] ?? null;
            }

            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlAsignaciones);

            $usuariosReemplazo = [];
            if ($responseAsignaciones->successful()) {
                $asignaciones     = $responseAsignaciones->json();
                $asignacionesList = $asignaciones['data'] ?? $asignaciones;
                
                foreach ($asignacionesList as $asignacion) {
                    $rolAsignacion = $asignacion['cargoNombre'] ?? $asignacion['nombre_rol'] ?? null;
                    $idAsignacion  = $asignacion['idAsignacion'] ?? $asignacion['id'] ?? null;
                    
                    if ($rolAsignacion == $rolActual && $idAsignacion != $programacion['id_asignacion']) {
                        $usuariosReemplazo[] = [
                            'id_asignacion'  => $idAsignacion,
                            'nombre_usuario' => $asignacion['usuarioNombre'] ?? $asignacion['nombre_usuario'] ?? 'Usuario',
                            'nombre_rol'     => $rolAsignacion
                        ];
                    }
                }
            }

            return response()->json([
                'success'    => true,
                'usuarios'   => $usuariosReemplazo,
                'rol_actual' => $rolActual
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function solicitarReemplazo(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validate([
            'id_programacion'         => 'required|integer',
            'motivo'                  => 'required|string|min:10|max:500',
            'id_asignacion_reemplazo' => 'required|integer',
        ]);

        try {
            $responseProgramacion = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->apiUrlProgramaciones . $validated['id_programacion'] . '/');

            if (!$responseProgramacion->successful()) {
                return redirect()->route('asistencia.index')->with('error', 'No se encontró la programación');
            }

            $programacion = $responseProgramacion->json();

            $payload = [
                'id_programacion'               => (int) $validated['id_programacion'],
                'id_asignacion_reemplazado_por' => (int) $validated['id_asignacion_reemplazo'],
                'motivo'                        => $validated['motivo'],
            ];

            Log::info('Enviando solicitud de reemplazo', ['payload' => $payload]);

            $reemplazoResponse = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->asJson()
                ->post('http://127.0.0.1:8001/reemplazos/solicitar/', $payload);

            if (!$reemplazoResponse->successful()) {
                $errorData = $reemplazoResponse->json();
                return redirect()->route('asistencia.index')
                    ->with('error', 'Error al crear solicitud: ' . ($errorData['error'] ?? 'Error desconocido'));
            }

            Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->asJson()
                ->post($this->apiUrlProgramaciones . $validated['id_programacion'] . '/actualizar/', [
                    'id_actividad'  => $programacion['id_actividad'],
                    'id_asignacion' => $programacion['id_asignacion'],
                    'fecha'         => $programacion['fecha'],
                    'estado'        => 'reemplazo_solicitado',
                ]);

            return redirect()->route('asistencia.index')->with('success', 'Solicitud de reemplazo enviada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error en solicitarReemplazo', ['error' => $e->getMessage()]);
            return redirect()->route('asistencia.index')->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function aprobarReemplazo($id_reemplazo)
    {
        try {
            $reemplazoResponse = Http::withHeaders($this->getHeaders())
                ->get('http://127.0.0.1:8001/reemplazos/' . $id_reemplazo . '/');
            
            if (!$reemplazoResponse->successful()) {
                return back()->with('error', 'No se encontró el reemplazo');
            }
            
            $reemplazo             = $reemplazoResponse->json();
            $idProgramacion        = $reemplazo['id_programacion'];
            $idAsignacionReemplazo = $reemplazo['id_asignacion_reemplazado_por'];
            
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post('http://127.0.0.1:8001/reemplazos/' . $id_reemplazo . '/aprobar/');

            if ($response->successful()) {
                $programacionResponse = Http::withHeaders($this->getHeaders())
                    ->asJson()
                    ->post('http://127.0.0.1:8001/programaciones/api/' . $idProgramacion . '/actualizar/', [
                        'id_asignacion' => $idAsignacionReemplazo,
                        'estado'        => 'reemplazado'
                    ]);
                
                if (!$programacionResponse->successful()) {
                    Log::warning('No se pudo actualizar la programación', ['id_programacion' => $idProgramacion]);
                }
                
                return redirect()->route('autorizaciones.index')->with('success', 'Reemplazo aprobado correctamente');
            } else {
                return back()->with('error', 'Error al aprobar reemplazo');
            }

        } catch (\Exception $e) {
            Log::error('Error al aprobar reemplazo', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function rechazarReemplazo($id_reemplazo)
    {
        try {
            $reemplazoResponse = Http::withHeaders($this->getHeaders())
                ->get('http://127.0.0.1:8001/reemplazos/' . $id_reemplazo . '/');
            
            if (!$reemplazoResponse->successful()) {
                return back()->with('error', 'No se encontró el reemplazo');
            }
            
            $reemplazo      = $reemplazoResponse->json();
            $idProgramacion = $reemplazo['id_programacion'];
            
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post('http://127.0.0.1:8001/reemplazos/' . $id_reemplazo . '/rechazar/', [
                    'observaciones' => request('observaciones', 'Rechazado por administrador')
                ]);

            if ($response->successful()) {
                $programacionResponse = Http::withHeaders($this->getHeaders())
                    ->asJson()
                    ->post('http://127.0.0.1:8001/programaciones/api/' . $idProgramacion . '/actualizar/', [
                        'estado' => 'pendiente'
                    ]);
                
                if (!$programacionResponse->successful()) {
                    Log::warning('No se pudo actualizar la programación a pendiente', ['id_programacion' => $idProgramacion]);
                }
                
                return redirect()->route('autorizaciones.index')->with('warning', 'Reemplazo rechazado y programación restaurada');
            } else {
                return back()->with('error', 'Error al rechazar reemplazo');
            }

        } catch (\Exception $e) {
            Log::error('Error al rechazar reemplazo', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function asistenciaIndex(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $usuario   = Session::get('usuario_api');
            $usuarioId = $usuario['id_usuario'] ?? null;
            
            if (!$usuarioId) {
                return view('asistencia.index', ['programaciones' => collect([])])
                    ->with('error', 'No se encontró información del usuario');
            }

            $estado      = $request->get('estado');
            $fecha_desde = $request->get('fecha_desde');
            $fecha_hasta = $request->get('fecha_hasta');
            $actividad   = $request->get('actividad');
           
            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->apiUrlAsignaciones);

            $idsAsignacionUsuario = [];
            $asignacionesMap      = [];
            
            if ($responseAsignaciones->successful()) {
                $asignaciones     = $responseAsignaciones->json();
                $asignacionesList = $asignaciones['data'] ?? $asignaciones;
                
                foreach ($asignacionesList as $asignacion) {
                    $idUsuario = $asignacion['idUsuario'] ?? $asignacion['usuario_id'] ?? $asignacion['id_usuario'] ?? null;
                    
                    if ($idUsuario == $usuarioId) {
                        $idAsignacion = $asignacion['idAsignacion'] ?? $asignacion['id'] ?? $asignacion['id_asignacion'] ?? null;
                        if ($idAsignacion) {
                            $idsAsignacionUsuario[] = $idAsignacion;
                            $asignacionesMap[$idAsignacion] = ($asignacion['usuarioNombre'] ?? 'Usuario') . ' - ' . ($asignacion['cargoNombre'] ?? 'Sin cargo');
                        }
                    }
                }
            }

            if (empty($idsAsignacionUsuario)) {
                return view('asistencia.index', ['programaciones' => collect([])])
                    ->with('info', 'No tienes asignaciones activas');
            }

            $responseProgramaciones = Http::withHeaders($this->getHeaders())->timeout(30)->get($this->apiUrlProgramaciones);
            $responseActividades    = Http::withHeaders($this->getHeaders())->timeout(30)->get($this->apiUrlActividades);

            $actividadesMap = [];
            if ($responseActividades->successful()) {
                $actividadesData = $responseActividades->json();
                $actividadesList = $actividadesData['data'] ?? $actividadesData ?? [];
                foreach ($actividadesList as $act) {
                    $actividadesMap[$act['id']] = $act['nombre_actividad'] ?? 'Sin nombre';
                }
            }

            $programaciones = collect([]);
            
            if ($responseProgramaciones->successful()) {
                $data                = $responseProgramaciones->json();
                $todasProgramaciones = collect($data);
                
                $programaciones = $todasProgramaciones->filter(function($item) use ($idsAsignacionUsuario) {
                    return in_array($item['id_asignacion'], $idsAsignacionUsuario);
                })->map(function($item) use ($actividadesMap, $asignacionesMap) {
                    return (object)[
                        'id_programacion'   => $item['id_programacion'],
                        'id_actividad'      => $item['id_actividad'],
                        'nombre_actividad'  => $actividadesMap[$item['id_actividad']] ?? 'Actividad ' . $item['id_actividad'],
                        'id_asignacion'     => $item['id_asignacion'],
                        'nombre_asignacion' => $asignacionesMap[$item['id_asignacion']] ?? 'Asignación ' . $item['id_asignacion'],
                        'fecha'             => $item['fecha'],
                        'estado'            => ucfirst(strtolower($item['estado']))
                    ];
                })->values();
                
                if ($estado && $estado !== '') {
                    $programaciones = $programaciones->filter(fn($p) => $p->estado === $estado)->values();
                }
                if ($fecha_desde && $fecha_desde !== '') {
                    $programaciones = $programaciones->filter(fn($p) => $p->fecha >= $fecha_desde)->values();
                }
                if ($fecha_hasta && $fecha_hasta !== '') {
                    $programaciones = $programaciones->filter(fn($p) => $p->fecha <= $fecha_hasta)->values();
                }
                if ($actividad && $actividad !== '') {
                    $al = strtolower($actividad);
                    $programaciones = $programaciones->filter(fn($p) => str_contains(strtolower($p->nombre_actividad), $al))->values();
                }
            }

            return view('asistencia.index', [
                'programaciones' => $programaciones,
                'filtros' => [
                    'estado'      => $estado,
                    'fecha_desde' => $fecha_desde,
                    'fecha_hasta' => $fecha_hasta,
                    'actividad'   => $actividad,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en asistenciaIndex', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return view('asistencia.index', ['programaciones' => collect([])])
                ->with('error', 'Error al cargar tus programaciones: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $search      = $request->get('search');
            $estado      = $request->get('estado');
            $fecha_desde = $request->get('fecha_desde');
            $fecha_hasta = $request->get('fecha_hasta');
            $ministerio  = $request->get('ministerio'); // ← NUEVO

            Log::info('Obteniendo programaciones desde API', ['url' => $this->apiUrlProgramaciones]);

            $responseProgramaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->apiUrlProgramaciones);

            if (!$responseProgramaciones->successful()) {
                Log::error('Error al obtener programaciones', [
                    'status' => $responseProgramaciones->status(),
                    'body'   => $responseProgramaciones->body()
                ]);
                return view('programacion.index', ['programaciones' => collect([])])
                    ->with('error', 'Error al obtener programaciones: ' . $responseProgramaciones->status());
            }

            $data               = $responseProgramaciones->json();
            $programacionesData = collect($data['data'] ?? $data ?? []);

            // Mapa de actividades
            $responseActividades = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:8001/actividades/api/actividades/');

            $actividadesMap = [];
            if ($responseActividades->successful()) {
                $actividadesData = $responseActividades->json();
                $actividadesList = $actividadesData['data'] ?? $actividadesData ?? [];
                foreach ($actividadesList as $act) {
                    $actividadesMap[$act['id']] = $act['nombre_actividad'] ?? 'Sin nombre';
                }
            }

            // Mapa de asignaciones + ministerios ← CAMBIO
            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:5431/api/asignaciones');

            $asignacionesMap = [];
            $ministerioMap   = []; // ← NUEVO

            if ($responseAsignaciones->successful()) {
                $asignacionesList = $responseAsignaciones->json();
                foreach ($asignacionesList as $asig) {
                    $id = $asig['idAsignacion'];
                    $asignacionesMap[$id] = ($asig['usuarioNombre'] ?? 'Usuario') . ' - ' . ($asig['cargoNombre'] ?? 'Sin cargo');
                    $ministerioMap[$id]   = $asig['ministerioNombre'] ?? 'General'; // ← NUEVO
                }
            }

            // Mapear programaciones ← CAMBIO: agrega $ministerioMap al use() y nombre_ministerio al objeto
            $programacionesCollection = $programacionesData->map(function ($item) use ($actividadesMap, $asignacionesMap, $ministerioMap) {
                $actividadId  = $item['id_actividad']  ?? null;
                $asignacionId = $item['id_asignacion'] ?? null;

                return (object) [
                    'id_programacion'   => $item['id_programacion'] ?? $item['id'] ?? null,
                    'id_actividad'      => $actividadId,
                    'nombre_actividad'  => $actividadesMap[$actividadId]   ?? 'Actividad no encontrada',
                    'id_asignacion'     => $asignacionId,
                    'nombre_asignacion' => $asignacionesMap[$asignacionId] ?? 'Asignación no encontrada',
                    'nombre_ministerio' => $ministerioMap[$asignacionId]   ?? 'General', // ← NUEVO
                    'fecha'             => $item['fecha']  ?? '',
                    'estado'            => $item['estado'] ?? 'pendiente',
                ];
            });

            // Aplicar filtros
            $programaciones = $programacionesCollection->filter(function ($p) use ($search, $estado, $fecha_desde, $fecha_hasta, $ministerio) {
                if ($search) {
                    $s = strtolower($search);
                    if (!str_contains(strtolower($p->nombre_actividad), $s) &&
                        !str_contains(strtolower($p->nombre_asignacion), $s)) {
                        return false;
                    }
                }
                if ($estado    && $p->estado !== $estado)       return false;
                if ($fecha_desde && $p->fecha < $fecha_desde)   return false;
                if ($fecha_hasta  && $p->fecha > $fecha_hasta)  return false;
                if ($ministerio  && strcasecmp($p->nombre_ministerio, $ministerio) !== 0) return false; // ← NUEVO
                return true;
            })->values();

            Log::info('Programaciones obtenidas', [
                'cantidad' => $programaciones->count(),
                'filtros'  => compact('search', 'estado', 'fecha_desde', 'fecha_hasta', 'ministerio')
            ]);

            $estados = $programacionesCollection->pluck('estado')->unique()->values();
            
            return view('programacion.index', compact('programaciones', 'estados'));
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión con API Python', ['error' => $e->getMessage(), 'url' => $this->apiUrlProgramaciones]);
            return view('programacion.index', ['programaciones' => collect([]), 'estados' => collect([])])
                ->with('error', 'No se pudo conectar con el servidor: ' . $this->apiUrlProgramaciones);
        } catch (\Exception $e) {
            Log::error('Excepción en index de programaciones', ['error' => $e->getMessage()]);
            return view('programacion.index', ['programaciones' => collect([]), 'estados' => collect([])])
                ->with('error', 'Error al conectar con el servidor: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . '/' . $id);

            if ($response->status() === 404) abort(404, 'Programación no encontrada');
            if (!$response->successful()) return back()->withErrors('Error al obtener la programación');

            $programacion = $response->json();
            return view('programacion.show', compact('programacion'));
            
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $responseActividades = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:8001/actividades/api/actividades/');

            $actividades = collect([]);
            if ($responseActividades->successful()) {
                $data            = $responseActividades->json();
                $actividadesList = $data['data'] ?? $data ?? [];
                
                \Log::info('Actividades recibidas', ['count' => count($actividadesList)]);

                $actividades = collect($actividadesList)->map(function ($item) {
                    return (object) [
                        'id_actividad'     => $item['id'] ?? null,
                        'nombre_actividad' => $item['nombre_actividad'] ?? 'Sin nombre',
                        'descripcion'      => $item['descripcion'] ?? '',
                        'hora_inicio'      => $item['hora_inicio'] ?? '',
                        'hora_fin'         => $item['hora_fin'] ?? '',
                        'ministerio'       => $item['nombre_ministerio'] ?? ''
                    ];
                });
            } else {
                \Log::error('Error al obtener actividades', [
                    'status' => $responseActividades->status(),
                    'body'   => $responseActividades->body()
                ]);
            }

            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:5431/api/asignaciones');

            $asignaciones = collect([]);
            if ($responseAsignaciones->successful()) {
                $asignacionesList = $responseAsignaciones->json();
                \Log::info('Asignaciones recibidas', ['count' => count($asignacionesList)]);
                $asignaciones = collect($asignacionesList)->map(function ($item) {
                    return (object) [
                        'id_asignacion'  => $item['idAsignacion'] ?? null,
                        'nombre_completo' => $item['usuarioNombre'] ?? 'Usuario',
                        'cargo'          => $item['cargoNombre'] ?? 'Sin cargo',
                    ];
                });
            }

            $programacion = (object)[
                'id_programacion' => '',
                'id_actividad'    => '',
                'id_asignacion'   => '',
                'fecha'           => date('Y-m-d'),
                'estado'          => 'pendiente',
            ];

            return view('programacion.create', compact('programacion', 'actividades', 'asignaciones'));
            
        } catch (\Exception $e) {
            \Log::error('Error al cargar formulario de creación', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            $actividades  = collect([]);
            $asignaciones = collect([]);
            $programacion = (object)[
                'id_programacion' => '',
                'id_actividad'    => '',
                'id_asignacion'   => '',
                'fecha'           => date('Y-m-d'),
                'estado'          => 'pendiente',
            ];
            
            return view('programacion.create', compact('programacion', 'actividades', 'asignaciones'))
                ->with('error', 'Error al cargar actividades y asignaciones: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validate([
            'id_actividad'  => 'required|integer',
            'id_asignacion' => 'required|integer',
            'fecha'         => 'required|date',
            'estado'        => 'nullable|string|in:pendiente,confirmado,reemplazado'
        ]);

        $estado  = strtolower($validated['estado'] ?? 'pendiente');
        $payload = [
            'id_actividad'  => (int) $validated['id_actividad'],
            'id_asignacion' => (int) $validated['id_asignacion'],
            'fecha'         => $validated['fecha'],
            'estado'        => $estado,
        ];

        try {
            $url = $this->apiUrlProgramaciones . 'crear/';
            Log::info('Creando programación', ['url' => $url, 'payload' => $payload]);

            $response = Http::withHeaders($this->getHeaders())->asJson()->timeout(30)->post($url, $payload);
            Log::info('Respuesta Django', ['status' => $response->status(), 'body' => $response->body()]);

            if (!$response->successful()) {
                Log::error('Error al crear programación', ['status' => $response->status(), 'body' => $response->body()]);
                return back()->withInput()->withErrors('Error al crear programación: ' . $response->status() . ' - ' . $response->body());
            }

            $result = $response->json();
            if (isset($result['success']) && $result['success'] === true) {
                return redirect()->route('programacion.index')->with('success', 'Programación creada correctamente');
            } else {
                return back()->withInput()->withErrors('Error: ' . ($result['error'] ?? 'Error desconocido'));
            }
                    
        } catch (\Exception $e) {
            Log::error('Excepción en store', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->apiUrlProgramaciones . $id . '/');
                
            if (!$response->successful()) abort(404, 'Programación no encontrada');
            
            $data = $response->json();

            $programacion = (object) [
                'id_programacion' => $data['id_programacion'] ?? $data['id'] ?? $id,
                'id_actividad'    => $data['id_actividad'] ?? '',
                'id_asignacion'   => $data['id_asignacion'] ?? '',
                'fecha'           => $data['fecha'] ?? '',
                'estado'          => $data['estado'] ?? 'pendiente',
                'hora_inicio'     => $data['hora_inicio'] ?? '',
                'hora_fin'        => $data['hora_fin'] ?? '',
            ];

            $responseActividades = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:8001/actividades/api/actividades/');

            $actividades = collect([]);
            if ($responseActividades->successful()) {
                $dataAct         = $responseActividades->json();
                $actividadesList = $dataAct['data'] ?? $dataAct ?? [];
                $actividades     = collect($actividadesList)->map(function ($item) {
                    return (object) [
                        'id_actividad'     => $item['id'] ?? null,
                        'nombre_actividad' => $item['nombre_actividad'] ?? 'Sin nombre',
                        'hora_inicio'      => $item['hora_inicio'] ?? '',
                        'hora_fin'         => $item['hora_fin'] ?? '',
                    ];
                });
            }

            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get('http://127.0.0.1:5431/api/asignaciones');

            $asignaciones = collect([]);
            if ($responseAsignaciones->successful()) {
                $asignacionesList = $responseAsignaciones->json();
                $asignaciones     = collect($asignacionesList)->map(function ($item) {
                    return (object) [
                        'id_asignacion' => $item['idAsignacion'] ?? null,
                        'texto'         => ($item['usuarioNombre'] ?? 'Usuario') . ' — ' . ($item['cargoNombre'] ?? 'Sin cargo'),
                    ];
                });
            }

            return view('programacion.edit', compact('programacion', 'actividades', 'asignaciones'));
            
        } catch (\Exception $e) {
            \Log::error('Error en edit', ['error' => $e->getMessage()]);
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validate([
            'id_actividad'  => 'required|integer',
            'id_asignacion' => 'required|integer',
            'fecha'         => 'required|date',
            'estado'        => 'nullable|string|in:Pendiente,Confirmado,Reemplazado'
        ]);

        $estado  = strtolower($validated['estado'] ?? 'pendiente');
        $payload = [
            'id_actividad'  => (int) $validated['id_actividad'],
            'id_asignacion' => (int) $validated['id_asignacion'],
            'fecha'         => $validated['fecha'],
            'estado'        => $estado,
        ];

        try {
            $url = $this->apiUrlProgramaciones . $id . '/actualizar/';
            Log::info('=== ACTUALIZANDO PROGRAMACIÓN ===', ['id' => $id, 'url' => $url, 'payload' => $payload]);

            $response = Http::withHeaders($this->getHeaders())->asJson()->timeout(30)->post($url, $payload);
            Log::info('RESPUESTA DE DJANGO', ['status' => $response->status(), 'body' => $response->body(), 'json' => $response->json()]);

            if (!$response->successful()) {
                return back()->withInput()->withErrors('Error al actualizar: ' . $response->status() . ' - ' . $response->body());
            }

            $result = $response->json();
            if (isset($result['success']) && $result['success'] === true) {
                return redirect()->route('programacion.index')->with('success', 'Programación actualizada correctamente');
            } else {
                return back()->withInput()->withErrors('Error: ' . ($result['error'] ?? 'Error desconocido'));
            }
                    
        } catch (\Exception $e) {
            Log::error('Excepción en update', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function cancelar($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->patch($this->apiUrlProgramaciones . '/' . $id . '/cancelar', ['estado' => 'cancelado']);

            if (!$response->successful()) return back()->withErrors('Error al cancelar programación');

            return redirect()->route('programacion.index')->with('success', 'Programación cancelada correctamente');
                
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $url = $this->apiUrlProgramaciones . $id . '/eliminar/';
            Log::info('=== ELIMINANDO PROGRAMACIÓN ===', ['id' => $id, 'url' => $url]);

            $response = Http::withHeaders($this->getHeaders())->asJson()->timeout(30)->delete($url);
            Log::info('RESPUESTA DE DJANGO', ['status' => $response->status(), 'body' => $response->body(), 'json' => $response->json()]);

            if (!$response->successful()) {
                return back()->withErrors('Error al eliminar programación: ' . $response->status() . ' - ' . $response->body());
            }

            $result = $response->json();
            if (isset($result['success']) && $result['success'] === true) {
                return redirect()->route('programacion.index')->with('success', 'Programación eliminada correctamente');
            } else {
                return back()->withErrors('Error: ' . ($result['error'] ?? 'Error desconocido'));
            }
                    
        } catch (\Exception $e) {
            Log::error('Excepción en destroy', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function getByDay($dia)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->apiUrlProgramaciones . '/fecha/' . $dia);
            if (!$response->successful()) return response()->json(['error' => 'Error al obtener programaciones'], 500);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEstadisticas()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->apiUrlProgramaciones . '/estadisticas');
            if (!$response->successful()) return response()->json(['error' => 'Error al obtener estadísticas'], 500);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}