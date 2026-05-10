<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario_api')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}