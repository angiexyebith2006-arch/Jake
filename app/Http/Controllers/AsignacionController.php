<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsignacionController extends Controller
{
    protected $api = 'http://127.0.0.1:5431/api';

    public function index()
    {
        $response = Http::get($this->api . '/asignaciones');

        $asignaciones = $response->successful()
            ? ($response->json() ?? [])
            : [];

        return view('perfil.asignacion.index', compact('asignaciones'));
    }

    public function create()
    {
        $usuarios = Http::get('http://127.0.0.1:5431/usuarios')->json() ?? [];

        $rolesResponse = Http::get($this->api . '/rol');
        $roles = $rolesResponse->successful() ? ($rolesResponse->json() ?? []) : [];

        $ministeriosResponse = Http::get($this->api . '/ministerio');
        $ministerios = $ministeriosResponse->successful()
            ? ($ministeriosResponse->json()['data'] ?? $ministeriosResponse->json())
            : [];

        $cargos = Http::get($this->api . '/cargos')->json() ?? [];

        return view('perfil.asignacion.create', compact(
            'usuarios',
            'roles',
            'ministerios',
            'cargos'
        ));
    }

    public function store(Request $request)
    {
        $response = Http::post($this->api . '/asignaciones/json', [
            'idUsuario'    => (int) $request->idUsuario,
            'idRol'        => (int) $request->idRol,
            'idMinisterio' => (int) $request->idMinisterio,
            'idCargo'      => $request->idCargo ? (int) $request->idCargo : null,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('asignaciones.index')
                ->with('success', 'Asignación creada correctamente');
        }

        return back()->withErrors([
            'error' => 'No se pudo crear la asignación'
        ]);
    }

    public function edit($id)
    {
        $response = Http::get($this->api . '/asignaciones/' . $id);

        $data = $response->json();
        $asignacion = $data['data'] ?? $data;

        $usuarios = Http::get('http://127.0.0.1:5431/usuarios')->json() ?? [];

        $roles = Http::get($this->api . '/rol')->json() ?? [];

        $ministeriosResponse = Http::get($this->api . '/ministerio');
        $ministerios = $ministeriosResponse->successful()
            ? ($ministeriosResponse->json()['data'] ?? $ministeriosResponse->json())
            : [];

        $cargos = Http::get($this->api . '/cargos')->json() ?? [];

        return view('perfil.asignacion.edit', compact(
            'asignacion',
            'usuarios',
            'roles',
            'ministerios',
            'cargos'
        ));
    }

    public function update(Request $request, $id)
    {
        $response = Http::withQueryParameters([
            'idUsuario'    => (int) $request->idUsuario,
            'idRol'        => (int) $request->idRol,
            'idMinisterio' => (int) $request->idMinisterio,
            'idCargo'      => $request->idCargo ? (int) $request->idCargo : null,
        ])->put($this->api . '/asignaciones/' . $id);

        if ($response->successful()) {
            return redirect()
                ->route('asignaciones.index')
                ->with('success', 'Asignación actualizada correctamente');
        }

        return back()->with('error', 'No se pudo actualizar');
    }

    public function destroy($id)
    {
        Http::delete($this->api . '/asignaciones/' . $id);

        return redirect()
            ->route('asignaciones.index')
            ->with('success', 'Asignación eliminada correctamente');
    }
}