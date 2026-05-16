<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class LoginUsuarioController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://localhost:5431/api/auth';
    }

    public function showLoginForm()
    {
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
            $response = Http::timeout(10)
                ->retry(1, 100)
                ->acceptJson()
                ->post($this->apiUrl . '/login', [
                    'correo' => $request->correo,
                    'clave'  => $request->clave
                ]);

            if ($response->successful()) {

                $usuarioJava = $response->json();

                Session::put('usuario_api', [
                    'id_usuario' => $usuarioJava['idUsuario'] ?? null,
                    'nombre'     => $usuarioJava['nombre'] ?? '',
                    'correo'     => $usuarioJava['correo'] ?? '',
                ]);

                $usuarioId = $usuarioJava['idUsuario'] ?? null;

                if ($usuarioId) {
                    $permisosResponse = Http::timeout(10)
                        ->retry(1, 100)
                        ->acceptJson()
                        ->get("http://127.0.0.1:5431/api/permisos/usuario/$usuarioId");

                    Log::info('Respuesta permisos', [
                        'body'   => $permisosResponse->body(),
                        'json'   => $permisosResponse->json(),
                        'status' => $permisosResponse->status()
                    ]);

                    if ($permisosResponse->successful()) {
                        $permisos = $permisosResponse->json();
                        Session::put('permisos_jake', $permisos);
                        Log::info('Permisos guardados correctamente', [
                            'usuario_id' => $usuarioId,
                            'permisos'   => $permisos
                        ]);
                    } else {
                        Log::warning('No se pudieron obtener permisos', [
                            'usuario_id' => $usuarioId,
                            'status'     => $permisosResponse->status(),
                            'body'       => $permisosResponse->body()
                        ]);
                        Session::put('permisos_jake', []);
                    }
                }

                return redirect()->route('mi-perfil');
            }

            return back()->withErrors([
                'correo' => 'Credenciales incorrectas'
            ]);

        } catch (ConnectionException $e) {
            Log::error('API caída', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'correo' => 'El servidor de autenticación está apagado o no disponible.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error login', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'correo' => 'Ocurrió un error inesperado.'
            ]);
        }
    }

    // ─────────────────────────────────────────────
    // RECUPERAR CONTRASEÑA
    // ─────────────────────────────────────────────

    public function showRecuperarForm()
    {
        return view('auth.recuperar');
    }

    public function enviarRecuperacion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ]);

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get('http://127.0.0.1:5431/usuarios');

            if ($response->successful()) {
                $usuarios = $response->json();

                $usuario = collect($usuarios)->firstWhere('correo', $request->correo);

                if ($usuario) {
                    return back()->with('status', 'Correo encontrado correctamente.');
                } else {
                    return back()->withErrors([
                        'correo' => 'No existe una cuenta con ese correo.'
                    ]);
                }
            }

            return back()->withErrors([
                'correo' => 'Error consultando usuarios.'
            ]);

        } catch (ConnectionException $e) {
            Log::error('API caída', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'correo' => 'Servidor no disponible.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error recuperación', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'correo' => 'Ocurrió un error inesperado.'
            ]);
        }
    }

    // ─────────────────────────────────────────────

    public function logout(Request $request)
    {
        Session::forget('usuario_api');
        Session::forget('permisos_jake');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Usuario cerró sesión');

        return redirect()->route('login');
    }
}