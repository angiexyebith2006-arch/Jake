<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ReemplazoController extends Controller
{
    protected string $apiUrlProgramaciones = 'http://127.0.0.1:8001/programaciones/api/';
    protected string $apiUrlAsignaciones = 'http://127.0.0.1:5431/api/asignaciones';

    // Verificar sesión (igual que en ProgramacionController)
    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')
                ->with('error', 'Por favor, inicie sesión para continuar.');
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

    // 1. Mostrar formulario de reemplazo
    public function create($id_programacion)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            // Obtener asignaciones (usuarios disponibles)
            $responseAsignaciones = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->apiUrlAsignaciones);

            $asignaciones = collect([]);

            if ($responseAsignaciones->successful()) {
                $data = $responseAsignaciones->json();

                $asignaciones = collect($data)->map(function ($item) {
                    return (object) [
                        'id_asignacion' => $item['idAsignacion'] ?? null,
                        'texto' => ($item['usuarioNombre'] ?? 'Usuario') . ' - ' . ($item['cargoNombre'] ?? 'Sin cargo'),
                    ];
                });
            }

            return view('reemplazo.create', compact('id_programacion', 'asignaciones'));

        } catch (\Exception $e) {
            Log::error('Error en create reemplazo', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors('Error al cargar el formulario de reemplazo');
        }
    }

    // 2. Guardar reemplazo
    public function store(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        // Validar datos
        $validated = $request->validate([
            'id_programacion' => 'required|integer',
            'id_asignacion'   => 'required|integer',
        ]);

        $id_programacion = $validated['id_programacion'];
        $nuevo_asignado = $validated['id_asignacion'];

        try {
            $url = $this->apiUrlProgramaciones . $id_programacion . '/actualizar/';

            Log::info('=== REALIZANDO REEMPLAZO ===', [
                'programacion' => $id_programacion,
                'nuevo_asignado' => $nuevo_asignado
            ]);

            //  llamada a Django
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->timeout(30)
                ->post($url, [
                    'estado' => 'reemplazado',
                    'id_asignacion' => $nuevo_asignado
                ]);

            Log::info('RESPUESTA DJANGO (REEMPLAZO)', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return back()->withInput()
                    ->withErrors('Error al realizar el reemplazo');
            }

            return redirect()->route('programacion.index')
                ->with('success', 'Reemplazo realizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error en store reemplazo', [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->withErrors('Error: ' . $e->getMessage());
        }
    }
}