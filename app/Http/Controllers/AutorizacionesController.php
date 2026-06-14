<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Log;

class AutorizacionesController extends Controller
{
    protected string $reemplazosApiUrl     = 'http://127.0.0.1:8001/reemplazos';
    protected string $autorizacionesApiUrl = 'http://127.0.0.1:8001/autorizaciones/api';
    protected string $programacionesApiUrl = 'http://127.0.0.1:8001/programaciones/api/';

    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión');
        }
        return null;
    }

    protected function getHeaders(): array
    {
        return ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
    }

    public function index()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $usuario           = Session::get('usuario_api', []);
        $autorizadorNombre = $usuario['nombre'] ?? ($usuario['name'] ?? 'Administrador');

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->reemplazosApiUrl . '/');

            if (!$response->successful()) {
                return view('autorizaciones.index', ['autorizaciones' => collect()])
                    ->with('error', 'Error al obtener los reemplazos.');
            }

            $lista = collect($response->json());

            $pendientes = $lista->filter(fn($r) => (int)($r['estado'] ?? -1) === 0);

            $autorizaciones = $pendientes->values()->map(function ($r) use ($autorizadorNombre) {
                return [
                    'id'                        => $r['id_reemplazo']               ?? 0,
                    'id_reemplazo'              => $r['id_reemplazo']               ?? null,
                    'id_programacion'           => $r['id_programacion']            ?? null,
                    'servidor_original_nombre'  => $r['nombre_servidor_original']   ?? 'Ver programación',
                    'servidor_reemplazo_nombre' => $r['nombre_servidor_reemplazo']  ?? 'No seleccionado',
                    'motivo'                    => $r['motivo']                     ?? 'Sin motivo',
                    'estado'                    => $r['estado_texto']               ?? 'Pendiente',
                    'fecha_autorizacion'        => now()->format('Y-m-d'),
                    'autorizador_nombre'        => $autorizadorNombre,
                ];
            });

            return view('autorizaciones.index', compact('autorizaciones'));

        } catch (\Exception $e) {
            Log::error('Error index autorizaciones', ['error' => $e->getMessage()]);
            return view('autorizaciones.index', ['autorizaciones' => collect()])
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function aprobar(Request $request, $id)
{
    $redirect = $this->checkAuth();
    if ($redirect) return $redirect;

    $observaciones = trim($request->input('observaciones', ''));
    $usuario       = Session::get('usuario_api', []);
    $idAutorizador = (int)($usuario['id_usuario'] ?? 1);

    try {
        // 1. Obtener el reemplazo
        $reemplazoResponse = Http::withHeaders($this->getHeaders())
            ->get($this->reemplazosApiUrl . '/' . $id . '/');
        
        if (!$reemplazoResponse->successful()) {
            return response()->json(['success' => false, 'message' => 'No se encontró el reemplazo'], 404);
        }
        
        $reemplazo = $reemplazoResponse->json();
        $idProgramacionOriginal = $reemplazo['id_programacion'];
        $idAsignacionReemplazo = $reemplazo['id_asignacion_reemplazado_por'];
        $idAsignacionOriginal = $reemplazo['id_asignacion_reemplazado'] ?? null;

        // 2. Obtener la programación original completa
        $programacionOriginalResponse = Http::withHeaders($this->getHeaders())
            ->get($this->programacionesApiUrl . $idProgramacionOriginal . '/');
        
        if (!$programacionOriginalResponse->successful()) {
            return response()->json(['success' => false, 'message' => 'No se encontró la programación original'], 404);
        }
        
        $programacionOriginal = $programacionOriginalResponse->json();

        // 3. CREAR NUEVA PROGRAMACIÓN para el usuario que reemplaza (estado PENDIENTE)
        $nuevaProgramacionPayload = [
            'id_actividad' => $programacionOriginal['id_actividad'],
            'id_asignacion' => $idAsignacionReemplazo,
            'fecha' => $programacionOriginal['fecha'],
            'estado' => 'pendiente', // ← CORREGIDO: pendiente para que pueda confirmar
            'reemplaza_a' => $idAsignacionOriginal,
            'id_reemplazo' => (int)$id
        ];

        Log::info('Creando nueva programación para reemplazo', ['payload' => $nuevaProgramacionPayload]);

        $crearProgramacionResponse = Http::withHeaders($this->getHeaders())
            ->asJson()
            ->post($this->programacionesApiUrl . 'crear/', $nuevaProgramacionPayload);

        if (!$crearProgramacionResponse->successful()) {
            Log::error('Error al crear nueva programación', [
                'status' => $crearProgramacionResponse->status(),
                'body' => $crearProgramacionResponse->body()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Error al crear la programación para el reemplazo'
            ], 500);
        }

        $nuevaProgramacion = $crearProgramacionResponse->json();

        // 4. Actualizar la programación original a estado 'reemplazado'
        $actualizarOriginalResponse = Http::withHeaders($this->getHeaders())
            ->asJson()
            ->post($this->programacionesApiUrl . $idProgramacionOriginal . '/actualizar/', [
                'id_actividad' => $programacionOriginal['id_actividad'],
                'id_asignacion' => $programacionOriginal['id_asignacion'],
                'fecha' => $programacionOriginal['fecha'],
                'estado' => 'reemplazado',
                'reemplazado_por' => $idAsignacionReemplazo,
                'id_reemplazo' => (int)$id
            ]);

        if (!$actualizarOriginalResponse->successful()) {
            Log::warning('No se pudo actualizar la programación original', [
                'id_programacion' => $idProgramacionOriginal
            ]);
        }

        // 5. Aprobar el reemplazo en Django
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->reemplazosApiUrl . '/' . $id . '/aprobar/');

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => $response->json()['error'] ?? 'Error al aprobar',
            ], $response->status());
        }

        // 6. Registrar autorización
        $autorizacionPayload = [
            'id_reemplazo' => (int)$id,
            'id_autorizador' => $idAutorizador,
            'fecha_autorizacion' => now()->format('Y-m-d'),
            'observaciones' => $observaciones ?: 'Aprobado - Se creó nueva programación para el reemplazo',
        ];

        Log::info('Registrando autorización', ['payload' => $autorizacionPayload]);

        Http::withHeaders($this->getHeaders())
            ->post($this->autorizacionesApiUrl . '/crear/', $autorizacionPayload);

        return response()->json([
            'success' => true, 
            'message' => 'Reemplazo aprobado correctamente. El usuario reemplazante debe confirmar su asistencia.',
            'data' => [
                'programacion_original' => $idProgramacionOriginal,
                'nueva_programacion' => $nuevaProgramacion['data']['id_programacion'] ?? $nuevaProgramacion['id_programacion'] ?? null
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error al aprobar reemplazo', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function rechazar(Request $request, $id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $observaciones = trim($request->input('observaciones', ''));
        if (empty($observaciones)) {
            return response()->json(['success' => false, 'message' => 'El motivo es requerido.'], 422);
        }

        try {
            // 1. Obtener el reemplazo para saber qué programación afecta
            $reemplazoResponse = Http::withHeaders($this->getHeaders())
                ->get($this->reemplazosApiUrl . '/' . $id . '/');
            
            if (!$reemplazoResponse->successful()) {
                return response()->json(['success' => false, 'message' => 'No se encontró el reemplazo'], 404);
            }
            
            $reemplazo = $reemplazoResponse->json();
            $idProgramacion = $reemplazo['id_programacion'];

            // 2. Rechazar el reemplazo en Django
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->reemplazosApiUrl . '/' . $id . '/rechazar/', [
                    'observaciones' => $observaciones,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['error'] ?? 'Error al rechazar',
                ], $response->status());
            }

            // 3. ¡CRUCIAL! Actualizar la programación a estado 'pendiente' para que vuelvan los botones
            $programacionResponse = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post($this->programacionesApiUrl . $idProgramacion . '/actualizar/', [
                    'estado' => 'pendiente'  // ← Esto es lo que hace que vuelvan los botones
                ]);

            if (!$programacionResponse->successful()) {
                Log::warning('No se pudo actualizar la programación a pendiente', [
                    'id_programacion' => $idProgramacion,
                    'response' => $programacionResponse->body()
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Reemplazo rechazado correctamente.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function testUrls()
    {
        $results = [];
        foreach ([
            'reemplazos'     => $this->reemplazosApiUrl . '/',
            'autorizaciones' => $this->autorizacionesApiUrl . '/',
        ] as $key => $url) {
            try {
                $r = Http::timeout(5)->withHeaders($this->getHeaders())->get($url);
                $results[$key] = ['url' => $url, 'status' => $r->status(), 'ok' => $r->successful()];
            } catch (\Exception $e) {
                $results[$key] = ['url' => $url, 'error' => $e->getMessage()];
            }
        }
        return response()->json($results);
    }
}