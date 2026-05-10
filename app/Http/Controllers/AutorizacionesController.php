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
            // 1. Obtener el reemplazo para saber qué programación afecta
            $reemplazoResponse = Http::withHeaders($this->getHeaders())
                ->get($this->reemplazosApiUrl . '/' . $id . '/');
            
            if (!$reemplazoResponse->successful()) {
                return response()->json(['success' => false, 'message' => 'No se encontró el reemplazo'], 404);
            }
            
            $reemplazo = $reemplazoResponse->json();
            $idProgramacion = $reemplazo['id_programacion'];
            $idAsignacionReemplazo = $reemplazo['id_asignacion_reemplazado_por'];

            // 2. Aprobar el reemplazo en Django
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->reemplazosApiUrl . '/' . $id . '/aprobar/');

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['error'] ?? 'Error al aprobar',
                ], $response->status());
            }

            // 3. Actualizar la programación: nueva asignación y estado 'reemplazado'
            $programacionResponse = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post($this->programacionesApiUrl . $idProgramacion . '/actualizar/', [
                    'id_asignacion' => $idAsignacionReemplazo,
                    'estado' => 'reemplazado'
                ]);

            if (!$programacionResponse->successful()) {
                Log::warning('No se pudo actualizar la programación', [
                    'id_programacion' => $idProgramacion
                ]);
            }

            // 4. Registrar autorización
            Http::withHeaders($this->getHeaders())
                ->post($this->autorizacionesApiUrl . '/crear/', [
                    'id_reemplazo'       => (int)$id,
                    'id_autorizador'     => $idAutorizador,
                    'fecha_autorizacion' => now()->format('Y-m-d'),
                    'observaciones'      => $observaciones ?: 'Aprobado',
                ]);

            return response()->json(['success' => true, 'message' => 'Reemplazo aprobado correctamente.']);

        } catch (\Exception $e) {
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