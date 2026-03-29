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


    public function index()
    {

        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $url = $this->apiUrlProgramaciones;
            Log::info('Obteniendo programaciones desde API', ['url' => $url]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($url);

            Log::info('Respuesta API Programaciones', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('Error al obtener programaciones', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return view('programacion.index', ['programaciones' => collect([])])
                    ->with('error', 'Error al obtener programaciones: ' . $response->status());
            }

            $data = $response->json();
            
            $programaciones = collect($data['data'] ?? $data ?? [])->map(function ($item) {
                return (object) [
                    'id_programacion' => $item['id_programacion'] ?? $item['id'] ?? null,
                    'id_actividad'    => $item['id_actividad'] ?? null,
                    'id_asignacion'   => $item['id_asignacion'] ?? null,
                    'fecha'           => $item['fecha'] ?? '',
                    'estado'          => $item['estado'] ?? 'pendiente',
                ];
            });

            Log::info('Programaciones obtenidas', [
                'cantidad' => $programaciones->count()
            ]);

            return view('programacion.index', compact('programaciones'));
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión con API Python', [
                'error' => $e->getMessage(),
                'url' => $this->apiUrlProgramaciones
            ]);
            
            return view('programacion.index', ['programaciones' => collect([])])
                ->with('error', 'No se pudo conectar con el servidor de programaciones. Verifique que la API de Python esté corriendo en: ' . $this->apiUrlProgramaciones);
                
        } catch (\Exception $e) {
            Log::error('Excepción en index de programaciones', [
                'error' => $e->getMessage()
            ]);
            
            return view('programacion.index', ['programaciones' => collect([])])
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

            if ($response->status() === 404) {
                abort(404, 'Programación no encontrada');
            }

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener la programación');
            }

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
            $data = $responseActividades->json();

            $actividadesList = $data['data'] ?? $data ?? [];
            
            \Log::info('Actividades recibidas', ['count' => count($actividadesList)]);

            $actividades = collect($actividadesList)->map(function ($item) {
                return (object) [
                    'id_actividad' => $item['id'] ?? null,
                    'nombre_actividad' => $item['nombre_actividad'] ?? 'Sin nombre',
                    'descripcion' => $item['descripcion'] ?? '',
                    'hora_inicio' => $item['hora_inicio'] ?? '',
                    'hora_fin' => $item['hora_fin'] ?? '',
                    'ministerio' => $item['nombre_ministerio'] ?? ''
                ];
            });
            
            \Log::info('Actividades mapeadas', ['count' => $actividades->count()]);
        } else {
            \Log::error('Error al obtener actividades', [
                'status' => $responseActividades->status(),
                'body' => $responseActividades->body()
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
                    'id_asignacion' => $item['idAsignacion'] ?? null,
                    'nombre_completo' => $item['usuarioNombre'] ?? 'Usuario',
                    'cargo' => $item['cargoNombre'] ?? 'Sin cargo',
                ];
            });
        }

        $programacion = (object)[
            'id_programacion' => '',
            'id_actividad' => '',
            'id_asignacion' => '',
            'fecha' => date('Y-m-d'),
            'estado' => 'pendiente',
        ];

        return view('programacion.create', compact('programacion', 'actividades', 'asignaciones'));
        
    } catch (\Exception $e) {
        \Log::error('Error al cargar formulario de creación', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $actividades = collect([]);
        $asignaciones = collect([]);
        $programacion = (object)[
            'id_programacion' => '',
            'id_actividad' => '',
            'id_asignacion' => '',
            'fecha' => date('Y-m-d'),
            'estado' => 'pendiente',
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
        'id_actividad' => 'required|integer',
        'id_asignacion' => 'required|integer',
        'fecha' => 'required|date',
        'estado' => 'nullable|string|in:pendiente,confirmado,reemplazado'
    ]);

    $estado = strtolower($validated['estado'] ?? 'pendiente');
    
    $payload = [
        'id_actividad' => (int) $validated['id_actividad'],
        'id_asignacion' => (int) $validated['id_asignacion'],
        'fecha' => $validated['fecha'],
        'estado' => $estado,
    ];

    try {
        $url = $this->apiUrlProgramaciones . 'crear/';
        
        Log::info('Creando programación', [
            'url' => $url,
            'payload' => $payload
        ]);

        $response = Http::withHeaders($this->getHeaders())
            ->asJson()
            ->timeout(30)
            ->post($url, $payload);

        Log::info('Respuesta Django', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Error al crear programación', [
                'status' => $response->status(),
                'body' => $errorBody
            ]);
            
            return back()->withInput()->withErrors('Error al crear programación: ' . $response->status() . ' - ' . $errorBody);
        }

        $result = $response->json();
        
        if (isset($result['success']) && $result['success'] === true) {
            return redirect()->route('programacion.index')
                ->with('success', 'Programación creada correctamente');
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
    
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiUrlProgramaciones . $id . '/');
            
        if (!$response->successful()) {
            abort(404, 'Programación no encontrada');
        }
        
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
            $dataAct = $responseActividades->json();
            $actividadesList = $dataAct['data'] ?? $dataAct ?? [];
            
            $actividades = collect($actividadesList)->map(function ($item) {
                return (object) [
                    'id_actividad' => $item['id'] ?? null,
                    'nombre_actividad' => $item['nombre_actividad'] ?? 'Sin nombre',
                    'hora_inicio' => $item['hora_inicio'] ?? '',
                    'hora_fin' => $item['hora_fin'] ?? '',
                ];
            });
        }

      
        $responseAsignaciones = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->get('http://127.0.0.1:5431/api/asignaciones');

        $asignaciones = collect([]);
        if ($responseAsignaciones->successful()) {
            $asignacionesList = $responseAsignaciones->json();
            
            $asignaciones = collect($asignacionesList)->map(function ($item) {
                return (object) [
                    'id_asignacion' => $item['idAsignacion'] ?? null,
                    'texto' => ($item['usuarioNombre'] ?? 'Usuario') . ' — ' . ($item['cargoNombre'] ?? 'Sin cargo'),
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
        'id_actividad' => 'required|integer',
        'id_asignacion' => 'required|integer',
        'fecha' => 'required|date',
        'estado' => 'nullable|string|in:Pendiente,Confirmado,Reemplazado'
    ]);

   
    $estado = strtolower($validated['estado'] ?? 'pendiente');
    
    $payload = [
        'id_actividad' => (int) $validated['id_actividad'],
        'id_asignacion' => (int) $validated['id_asignacion'],
        'fecha' => $validated['fecha'],
        'estado' => $estado,
    ];

    try {
     
        $url = $this->apiUrlProgramaciones . $id . '/actualizar/';
        
        Log::info('=== ACTUALIZANDO PROGRAMACIÓN ===', [
            'id' => $id,
            'url' => $url,
            'payload' => $payload,
        ]);

        $response = Http::withHeaders($this->getHeaders())
            ->asJson()
            ->timeout(30)
            ->post($url, $payload);  
        Log::info('RESPUESTA DE DJANGO', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if (!$response->successful()) {
            $errorBody = $response->body();
            return back()->withInput()->withErrors('Error al actualizar: ' . $response->status() . ' - ' . $errorBody);
        }

        $result = $response->json();
        
        if (isset($result['success']) && $result['success'] === true) {
            return redirect()->route('programacion.index')
                ->with('success', 'Programación actualizada correctamente');
        } else {
            return back()->withInput()->withErrors('Error: ' . ($result['error'] ?? 'Error desconocido'));
        }
                
    } catch (\Exception $e) {
        Log::error('Excepción en update', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
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
                ->patch($this->apiUrlProgramaciones . '/' . $id . '/cancelar', [
                    'estado' => 'cancelado'
                ]);

            if (!$response->successful()) {
                return back()->withErrors('Error al cancelar programación');
            }

            return redirect()->route('programacion.index')
                ->with('success', 'Programación cancelada correctamente');
                
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
        
        Log::info('=== ELIMINANDO PROGRAMACIÓN ===', [
            'id' => $id,
            'url' => $url,
        ]);

        $response = Http::withHeaders($this->getHeaders())
            ->asJson()
            ->timeout(30)
            ->delete($url);  

        Log::info('RESPUESTA DE DJANGO', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if (!$response->successful()) {
            $errorBody = $response->body();
            return back()->withErrors('Error al eliminar programación: ' . $response->status() . ' - ' . $errorBody);
        }

        $result = $response->json();
        
        if (isset($result['success']) && $result['success'] === true) {
            return redirect()->route('programacion.index')
                ->with('success', 'Programación eliminada correctamente');
        } else {
            return back()->withErrors('Error: ' . ($result['error'] ?? 'Error desconocido'));
        }
                
    } catch (\Exception $e) {
        Log::error('Excepción en destroy', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withErrors('Error: ' . $e->getMessage());
    }
}

    public function getByDay($dia)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . '/fecha/' . $dia);

            if (!$response->successful()) {
                return response()->json(['error' => 'Error al obtener programaciones'], 500);
            }

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
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrlProgramaciones . '/estadisticas');

            if (!$response->successful()) {
                return response()->json(['error' => 'Error al obtener estadísticas'], 500);
            }

            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}