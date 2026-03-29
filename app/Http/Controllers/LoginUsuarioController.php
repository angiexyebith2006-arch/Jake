<?php
// app/Http/Controllers/LoginUsuarioController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginUsuarioController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://localhost:5431/api/auth';
    }

    public function showLoginForm()
    {
        // Si ya está autenticado, NO mostrar el formulario de login
        if (Session::has('usuario_api')) {
            Log::info('Usuario ya autenticado, redirigiendo a dashboard');
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'clave'  => 'required|string',
        ]);

        try {
            Log::info('Intento de login', ['correo' => $request->correo]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->apiUrl . '/login', [
                    'correo' => $request->correo,
                    'clave'  => $request->clave
                ]);

            if ($response->successful()) {
                $usuarioJava = $response->json();
                
                // Guardar en sesión
                Session::put('usuario_api', [
                    'id_usuario' => $usuarioJava['id_usuario'] ?? $usuarioJava['idUsuario'] ?? null,
                    'nombre'     => $usuarioJava['nombre'] ?? '',
                    'correo'     => $usuarioJava['correo'] ?? $request->correo,
                    'telefono'   => $usuarioJava['telefono'] ?? null,
                    'activo'     => $usuarioJava['activo'] ?? true,
                ]);
                
                Log::info('Login exitoso, sesión guardada', [
                    'session_data' => Session::get('usuario_api')
                ]);
                
                // Redirigir directamente al dashboard SIN regenerar sesión
                return redirect()->route('dashboard')
                    ->with('success', '¡Bienvenido ' . ($usuarioJava['nombre'] ?? 'Usuario') . '!');
                
            } else {
                $errorMessage = $response->json()['message'] ?? 'Credenciales incorrectas';
                
                Log::warning('Login fallido', [
                    'correo' => $request->correo,
                    'error' => $errorMessage
                ]);
                
                return back()
                    ->withErrors(['correo' => $errorMessage])
                    ->withInput($request->only('correo'));
            }
            
        } catch (\Exception $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'correo' => $request->correo
            ]);
            
            return back()
                ->withErrors(['correo' => 'Error: ' . $e->getMessage()])
                ->withInput($request->only('correo'));
        }
    }

    public function logout(Request $request)
    {
        Session::forget('usuario_api');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Usuario cerró sesión');
        
        return redirect()->route('login');
    }
}