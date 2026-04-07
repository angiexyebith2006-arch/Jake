<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AutorizacionesController extends Controller
{
    protected string $apiUrl = 'http://127.0.0.1:8001/autorizaciones';

    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión');
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

    /**
     * 🔹 LISTAR AUTORIZACIONES
     */
    public function index()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl);

            if (!$response->successful()) {
                return view('autorizaciones.index', [
                    'autorizaciones' => collect([])
                ])->with('error', 'Error al obtener autorizaciones');
            }

            $data = $response->json();

            // 🔥 FIX CLAVE
            $autorizaciones = collect($data['data'] ?? []);

            return view('autorizaciones.index', compact('autorizaciones'));

        } catch (\Exception $e) {
            Log::error('Error index autorizaciones', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 CREAR SOLICITUD
     */
    public function store(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validate([
            'id_reemplazo' => 'required|integer',
            'id_autorizador' => 'required|integer',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiUrl, $validated);

            if (!$response->successful()) {
                return back()->withErrors('Error al crear autorización');
            }

            return back()->with('success', 'Autorización creada correctamente');

        } catch (\Exception $e) {
            Log::error('Error store autorizaciones', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 APROBAR (OPCIONAL SI YA LO HACES EN DJANGO)
     */
    public function aprobar($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiUrl . "/$id/aprobar");

            if (!$response->successful()) {
                return back()->withErrors('Error al aprobar');
            }

            return back()->with('success', 'Autorización aprobada');

        } catch (\Exception $e) {
            Log::error('Error aprobar', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 RECHAZAR
     */
    public function rechazar($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiUrl . "/$id/rechazar");

            if (!$response->successful()) {
                return back()->withErrors('Error al rechazar');
            }

            return back()->with('success', 'Solicitud rechazada');

        } catch (\Exception $e) {
            Log::error('Error rechazar', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}