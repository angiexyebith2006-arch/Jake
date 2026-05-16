<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    protected string $apiUrl = 'http://127.0.0.1:5431';

    /**
     * Paso 1: Mostrar formulario para ingresar correo.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Paso 2: Verificar correo, generar token y enviar email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ]);

        // Verificar si el correo existe en la API Java
        $response = Http::get("{$this->apiUrl}/usuarios/verificar-correo", [
            'correo' => $request->correo,
        ]);

        $data = $response->json();

        if (!($data['existe'] ?? false)) {
            return back()->withErrors([
                'correo' => 'No encontramos ninguna cuenta con ese correo.'
            ]);
        }

        // Generar token único y guardarlo en cache por 30 minutos
        $token = Str::random(64);
        Cache::put("reset_token_{$token}", $request->correo, now()->addMinutes(30));

        // Construir el link de recuperación
        $resetLink = route('password.reset.form', ['token' => $token]);

        // Enviar correo
        Mail::send('auth.reset-password-email', [
            'nombre'    => $data['nombre'],
            'resetLink' => $resetLink,
        ], function ($message) use ($request, $data) {
            $message->to($request->correo, $data['nombre'])
                    ->subject('Recuperar contraseña - JAKE');
        });

        return back()->with('success', '¡Correo enviado! Revisa tu bandeja de entrada y sigue el enlace para restablecer tu contraseña.');
    }

    /**
     * Paso 3: Mostrar formulario para nueva contraseña.
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');

        // Validar que el token existe y no ha expirado
        if (!Cache::has("reset_token_{$token}")) {
            return redirect()->route('password.forgot')
                             ->withErrors(['correo' => 'El enlace ha expirado o no es válido. Solicita uno nuevo.']);
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Paso 4: Cambiar la contraseña.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $token = $request->token;

        // Validar token
        if (!Cache::has("reset_token_{$token}")) {
            return back()->withErrors(['token' => 'El enlace ha expirado. Solicita uno nuevo.']);
        }

        $correo = Cache::get("reset_token_{$token}");

        // Llamar a la API Java para cambiar la clave
        $response = Http::post("{$this->apiUrl}/usuarios/cambiar-clave", [
            'correo'    => $correo,
            'nuevaClave' => $request->password,
        ]);

        if (!$response->successful()) {
            return back()->withErrors(['password' => 'No se pudo actualizar la contraseña. Intenta nuevamente.']);
        }

        // Eliminar el token usado
        Cache::forget("reset_token_{$token}");

        return redirect()->route('login')
                         ->with('success', '¡Contraseña actualizada correctamente! Ya puedes iniciar sesión.');
    }
}