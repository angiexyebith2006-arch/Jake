<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginUsuarioController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'correo' => 'required|email',
        'clave'  => 'required|string',
    ]);

    $usuario = Usuario::where('correo', $request->correo)
        ->where('clave', $request->clave)
        ->where('activo', true)
        ->first();

    if (!$usuario) {
        return back()->withErrors([
            'correo' => 'Credenciales incorrectas',
        ]);
    }

    Auth::login($usuario);
    $request->session()->regenerate();

    return redirect()->route('perfil.index');
}


    public function logout()
    {
        Session::forget('usuario');
        return redirect()->route('login');
    }
}
