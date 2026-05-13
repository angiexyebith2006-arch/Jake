<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RolController extends Controller
{
    protected string $api = 'http://127.0.0.1:5431/api';

    public function index()
    {
        $roles = Http::get("{$this->api}/rol")->json() ?? [];
        return view('perfil.asignacion.rol.index', compact('roles'));
    }

    public function create()
    {
        return view('perfil.asignacion.rol.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:60',   // ← nombre, no nombre_rol
            'descripcion' => 'nullable|string|max:255',
        ]);

        $response = Http::post("{$this->api}/rol", [
            'nombre'      => $request->nombre,            // ← nombre, no nombre_rol
            'descripcion' => $request->descripcion ?? '',
        ]);

        if ($response->successful()) {
            return redirect()->route('rol.index')
                             ->with('success', 'Rol creado correctamente.');
        }

        return back()->with('error', 'No se pudo crear el rol.')
                     ->withInput();
    }

    public function edit(int $id)
    {
        $response = Http::get("{$this->api}/rol/{$id}");
        $body     = $response->json();
        $rol      = $body['data'] ?? $body;

        if (empty($rol['id'])) {
            return redirect()->route('rol.index')
                             ->with('error', 'Rol no encontrado.');
        }

        return view('perfil.asignacion.rol.edit', compact('rol'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $response = Http::put("{$this->api}/rol/{$id}", [
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion ?? '',
        ]);

        if ($response->successful()) {
            return redirect()->route('rol.index')
                             ->with('success', 'Rol actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo actualizar el rol.')
                     ->withInput();
    }

    public function destroy(int $id)
    {
        $response = Http::delete("{$this->api}/rol/{$id}");

        if ($response->successful()) {
            return redirect()->route('rol.index')
                             ->with('success', 'Rol eliminado correctamente.');
        }

        return redirect()->route('rol.index')
                         ->with('error', 'No se pudo eliminar el rol.');
    }

    public function show(int $id)
    {
        $response = Http::get("{$this->api}/rol/{$id}");
        $body     = $response->json();
        $rol      = $body['data'] ?? $body;

        return view('perfil.asignacion.rol.show', compact('rol'));
    }
}