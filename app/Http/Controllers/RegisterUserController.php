<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class RegisterUserController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function store(Request $request)
    {
        // Validación del formulario con mensajes personalizados
        $validated = $request->validate([
            'nombre'   => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z]+$/'],
            'correo'   => ['required', 'string', 'email', 'max:100', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'telefono' => ['required', 'nullable', 'regex:/^\d{7,15}$/'],
            'activo'   => ['required', 'nullable', 'boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            // Nombre
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.string'   => 'El nombre debe ser un texto válido.',
            'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',
            'nombre.regex'    => 'EL nombre no debe tener digitos',
            // Correo
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.string'   => 'El correo debe ser un texto válido.',
            'correo.email'    => 'El correo electrónico no tiene un formato válido. Ejemplo: usuario@dominio.com',
            'correo.max'      => 'El correo no puede tener más de 100 caracteres.',
            'correo.regex'    => 'El correo no debe tener caracteres especiales y el dominio debe estar completo. Ej. adming@gmail.com',

            // Teléfono
            'telefono.regex'  => ' El teléfono debe contener SOLO números y tener entre 7 y 15 dígitos. No uses espacios, guiones ni letras.',
            'telefono.required' =>'El telefono es obligatorio',
            // Activo
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
            'activo.required' =>'El estado es obligatorio',
            // Contraseña
            'password.required'  => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden. Por favor, verifícalas.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Enviar datos a la API Spring Boot
        $response = Http::post(
            config('services.usuarios_api.base_url') . '/usuarios',
            [
                'nombre'   => $request->nombre,
                'correo'   => $request->correo,
                'telefono' => $request->telefono,
                'activo'   => $request->boolean('activo', true),
                'clave'    => $request->password,
            ]
        );

        // Manejo de errores de la API
        if (!$response->successful()) {
            $this->handleApiError($response, $request);
        }

        // Registro exitoso
        return redirect()
            ->route('login')
            ->with('success', '✅ ¡Usuario registrado correctamente! Ya puedes iniciar sesión.');
    }

    /**
     * Mostrar formulario de registro
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Manejar errores de la API
     */
    private function handleApiError($response, $request)
    {
        $body = $response->json();
        $statusCode = $response->status();

        // Log para depuración
        Log::error('Error en registro de usuario', [
            'status' => $statusCode,
            'body'   => $body,
            'input'  => $request->except('password')
        ]);

        // Correo duplicado (409 Conflict)
        if ($statusCode === 409) {
            throw ValidationException::withMessages([
                'correo' => ['❌ Este correo electrónico ya está registrado. Por favor, usa otro o inicia sesión.'],
            ]);
        }

        // Errores de validación de la API (400 Bad Request)
        if ($statusCode === 400 && isset($body['errors'])) {
            $this->handleValidationErrors($body['errors']);
        }

        // Error específico para teléfono
        if (isset($body['telefono'])) {
            throw ValidationException::withMessages([
                'telefono' => ['📱 ' . $body['telefono']],
            ]);
        }

        // Error genérico
        $mensajeError = 'No fue posible registrar el usuario.';
        if (isset($body['message'])) {
            $mensajeError .= ' Detalle: ' . $body['message'];
        }

        throw ValidationException::withMessages([
            'general' => [$mensajeError],
        ]);
    }

    /**
     * Mapear errores de validación de Java a campos de Laravel
     */
    private function handleValidationErrors($errors)
    {
        $erroresMapeados = [];

        foreach ($errors as $campo => $mensaje) {
            $erroresMapeados[$this->mapFieldName($campo)] = $this->getFieldIcon($campo) . ' ' . $mensaje;
        }

        if (!empty($erroresMapeados)) {
            throw ValidationException::withMessages($erroresMapeados);
        }
    }

    /**
     * Mapear nombres de campos de Java a Laravel
     */
    private function mapFieldName($campo)
    {
        $map = [
            'clave'    => 'password',
            'correo'   => 'correo',
            'nombre'   => 'nombre',
            'telefono' => 'telefono',
        ];

        return $map[$campo] ?? $campo;
    }

    /**
     * Obtener ícono según el campo
     */
    private function getFieldIcon($campo)
    {
        $icons = [
            'clave'    => '🔒',
            'correo'   => '📧',
            'nombre'   => '👤',
            'telefono' => '📱',
        ];

        return $icons[$campo] ?? '⚠️';
    }
}