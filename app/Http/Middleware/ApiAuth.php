<?php
// app/Http/Middleware/ApiAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Si NO está autenticado y NO está en login, redirigir a login
        if (!Session::has('usuario_api') && !$request->is('login')) {
            return redirect()->route('login');
        }
        
        // Si está autenticado y está en login, redirigir a dashboard
        if (Session::has('usuario_api') && $request->is('login')) {
            return redirect()->route('dashboard');
        }
        
        return $next($request);
    }
}