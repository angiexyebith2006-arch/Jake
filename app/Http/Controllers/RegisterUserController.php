<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisterUserController extends Controller
{
    public function store(Request $request)
    {
        // Validación del formulario con mensajes personalizados en español
        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'correo'   => ['required', 'string', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'activo'   => ['nullable', 'boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            // Nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string'   => 'El nombre debe ser un texto válido.',
            'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',

            // Correo
            'correo.required' => 'El correo es obligatorio.',
            'correo.string'   => 'El correo debe ser un texto válido.',
            'correo.email'    => 'El correo no tiene un formato válido.',
            'correo.max'      => 'El correo no puede tener más de 100 caracteres.',

            // Teléfono
            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max'    => 'El teléfono no puede tener más de 20 caracteres.',

            // Activo
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',

            // Contraseña
            'password.required'  => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'       => 'La contraseña es demasiado larga.',
            'password.string'    => 'La contraseña debe ser un texto válido.',
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
            $body = $response->json();

            // Correo duplicado
            if (
                isset($body['message']) &&
                str_contains(strtolower($body['message']), 'ya existe la llave') &&
                str_contains(strtolower($body['message']), 'correo')
            ) {
                throw ValidationException::withMessages([
                    'correo' => ['Este correo ya está registrado.'],
                ]);
            }

            // Error genérico
            throw ValidationException::withMessages([
                'correo' => ['No fue posible registrar el usuario.'],
            ]);
        }

        // Registro exitoso
        return redirect()
            ->route('perfil.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function register()
    {
        return view('auth.register');
    }
}