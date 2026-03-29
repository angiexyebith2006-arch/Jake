<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;

class RegisterUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'correo'   => ['required', 'string', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'activo'   => ['boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // llamada de la api
        $response = Http::post(config('services.usuarios_api.base_url') . '/usuarios', [
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'telefono' => $request->telefono,
            'activo'   => $request->boolean('activo', true),
            'clave'    => $request->password,
        ]);

        if (!$response->successful()) {
        return dd($response->status(), $response->body());
        }

        $data = $response->json(); 


        return redirect(route('dashboard'));
    }

    public function register()
    {
        return view('auth.register');
    }

}