<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PermisoController extends Controller
{
    protected string $apiUrlPermiso = 'http://127.0.0.1:5431/permisos';

    public function index()
    {
        try {
            $response = Http::get($this->apiUrlPermiso);

            if ($response->successful()) {
                $permisos = $response->json()['data'] ?? [];
            } else {
                $permisos = [];
            }
        } catch (\Exception $e) {
            $permisos = [];
        }

        return view('perfil.permiso.index', compact('permisos'));
    }

    public function edit($id)
{
    return view('perfil.permiso.edit', compact('id'));
}

public function create()
{
    return view('perfil.permiso.create');
}

}