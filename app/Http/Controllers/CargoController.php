<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CargoController extends Controller
{
    protected string $api = 'http://127.0.0.1:5431/api';

    /**
     * Listado de cargos.
     * La API devuelve un array directo (no envuelto en "data").
     */
    public function index()
    {
        $response = Http::get("{$this->api}/cargos");
        $cargos   = $response->json() ?? [];   // ← array directo, sin clave "data"

        return view('perfil.asignacion.cargo.index', compact('cargos'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('perfil.asignacion.cargo.create');
    }

    /**
     * Guardar nuevo cargo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreCargo' => 'required|string|max:80',
        ]);

        $response = Http::post("{$this->api}/cargos", [
            'nombreCargo' => $request->nombreCargo,
        ]);

        if ($response->successful()) {
            return redirect()->route('cargo.index')
                             ->with('success', 'Cargo creado correctamente.');
        }

        return back()->with('error', 'No se pudo crear el cargo. Intenta nuevamente.')
                     ->withInput();
    }

    /**
     * Formulario de edición.
     * La API puede devolver el objeto directamente o en "data".
     */
    public function edit(int $id)
    {
        $response = Http::get("{$this->api}/cargos/{$id}");
        $body     = $response->json();
        $cargo    = $body['data'] ?? $body;   // soporta ambos formatos

        if (empty($cargo['idCargo'])) {
            return redirect()->route('cargo.index')
                             ->with('error', 'Cargo no encontrado.');
        }

        return view('perfil.asignacion.cargo.edit', compact('cargo'));
    }

    /**
     * Actualizar cargo existente.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombreCargo' => 'required|string|max:80',
        ]);

        $response = Http::put("{$this->api}/cargos/{$id}", [
            'nombreCargo' => $request->nombreCargo,
        ]);

        if ($response->successful()) {
            return redirect()->route('cargo.index')
                             ->with('success', 'Cargo actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo actualizar el cargo.')
                     ->withInput();
    }

    /**
     * Eliminar cargo.
     */
    public function destroy(int $id)
    {
        $response = Http::delete("{$this->api}/cargos/{$id}");

        if ($response->successful()) {
            return redirect()->route('cargo.index')
                             ->with('success', 'Cargo eliminado correctamente.');
        }

        return redirect()->route('cargo.index')
                         ->with('error', 'No se pudo eliminar el cargo.');
    }
}