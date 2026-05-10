<?php


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
                'id_usuario' => $usuarioJava['id_usuario'] ?? null,
                'nombre'     => $usuarioJava['nombre'] ?? '',
                'correo'     => $usuarioJava['correo'] ?? '',
            ]);

            return redirect()
                ->route('programacion.index');
        }

        return back()->withErrors([
            'correo' => 'Credenciales incorrectas'
        ]);

    } catch (ConnectionException $e) {

        Log::error('API caída', [
            'error' => $e->getMessage()
        ]);

        return back()->withErrors([
            'correo' => 'El servidor de autenticación está apagado o no disponible.'
        ]);

    } catch (\Exception $e) {

        Log::error('Error login', [
            'error' => $e->getMessage()
        ]);

        return back()->withErrors([
            'correo' => 'Ocurrió un error inesperado.'
        ]);
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