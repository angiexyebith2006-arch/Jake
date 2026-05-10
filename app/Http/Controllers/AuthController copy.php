<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
    
        $request->session()->regenerate();
        return redirect()->route('dashboard'); 
    }

  
    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ]);
}

  
    public function logout()
    {
        Session::forget('usuario');
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
