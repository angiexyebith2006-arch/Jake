<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MinisterioController extends Controller
{
    protected string $api = 'http://127.0.0.1:5431/api';

    /**
     * Listado de ministerios.
     */
    public function index()
    {
        $response    = Http::get("{$this->api}/ministerios");
        $body        = $response->json();
        $ministerios = $body['data'] ?? [];           // ← usa la clave "data" de la API

        return view('perfil.asignacion.ministerio.index', compact('ministerios'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('perfil.asignacion.ministerio.create');
    }

    /**
     * Guardar nuevo ministerio.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreMinisterio' => 'required|string|max:100',
            'descripcion'      => 'nullable|string|max:300',
        ]);

        $response = Http::post("{$this->api}/ministerios", [
            'nombreMinisterio' => $request->nombreMinisterio,
            'descripcion'      => $request->descripcion ?? '',
        ]);

        if ($response->successful()) {
            return redirect()->route('ministerio.index')
                             ->with('success', 'Ministerio creado correctamente.');
        }

        return back()->with('error', 'No se pudo crear el ministerio. Intenta nuevamente.')
                     ->withInput();
    }

    /**
     * Formulario de edición.
     */
    public function edit(int $id)
    {
        $response   = Http::get("{$this->api}/ministerios/{$id}");
        $body       = $response->json();

        // La API puede devolver {data: {...}} o el objeto directo
        $ministerio = $body['data'] ?? $body;

        if (empty($ministerio['idMinisterio'])) {
            return redirect()->route('ministerio.index')
                             ->with('error', 'Ministerio no encontrado.');
        }

        return view('perfil.asignacion.ministerio.edit', compact('ministerio'));
    }

    /**
     * Actualizar ministerio existente.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombreMinisterio' => 'required|string|max:100',
            'descripcion'      => 'nullable|string|max:300',
        ]);

        $response = Http::put("{$this->api}/ministerios/{$id}", [
            'nombreMinisterio' => $request->nombreMinisterio,
            'descripcion'      => $request->descripcion ?? '',
        ]);

        if ($response->successful()) {
            return redirect()->route('ministerio.index')
                             ->with('success', 'Ministerio actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo actualizar el ministerio.')
                     ->withInput();
    }

    /**
     * Eliminar ministerio.
     */
    public function destroy(int $id)
    {
        $response = Http::delete("{$this->api}/ministerios/{$id}");

        if ($response->successful()) {
            return redirect()->route('ministerio.index')
                             ->with('success', 'Ministerio eliminado.');
        }

        return redirect()->route('ministerio.index')
                         ->with('error', 'No se pudo eliminar el ministerio.');
    }
}