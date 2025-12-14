<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'string', 'email', 'max:100', 'unique:usuarios,correo'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'activo' => ['boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'activo' => $request->boolean('activo', true),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($usuario));

        // Crear también el usuario en la tabla users para mantener compatibilidad con Jetstream
        \App\Models\User::create([
            'name' => $request->nombre,
            'email' => $request->correo,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($usuario);

        return redirect(route('dashboard', absolute: false));
    }
}