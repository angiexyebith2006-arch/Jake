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
            ? ($response->json()['data'] ?? $response->json() ?? [])
            : [];

        return view('perfil.asignacion.index', compact('asignaciones'));
    }

    public function create()
    {
        $usuariosResponse = Http::get('http://127.0.0.1:5431/usuarios');
        $usuarios = $usuariosResponse->successful()
            ? ($usuariosResponse->json() ?? [])
            : [];

        $rolesResponse = Http::get($this->api . '/rol');
        $roles = $rolesResponse->successful()
            ? ($rolesResponse->json()['data'] ?? $rolesResponse->json() ?? [])
            : [];

        $ministeriosResponse = Http::get($this->api . '/ministerios');
        $ministerios = $ministeriosResponse->successful()
            ? ($ministeriosResponse->json()['data'] ?? $ministeriosResponse->json() ?? [])
            : [];

        $cargosResponse = Http::get($this->api . '/cargos');
        $cargos = $cargosResponse->successful()
            ? ($cargosResponse->json()['data'] ?? $cargosResponse->json() ?? [])
            : [];

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

        $usuariosResponse = Http::get('http://127.0.0.1:5431/usuarios');
        $usuarios = $usuariosResponse->successful()
            ? ($usuariosResponse->json() ?? [])
            : [];

        $rolesResponse = Http::get($this->api . '/rol');
        $roles = $rolesResponse->successful()
            ? ($rolesResponse->json()['data'] ?? $rolesResponse->json() ?? [])
            : [];

        $ministeriosResponse = Http::get($this->api . '/ministerios');
        $ministerios = $ministeriosResponse->successful()
            ? ($ministeriosResponse->json()['data'] ?? $ministeriosResponse->json() ?? [])
            : [];

        $cargosResponse = Http::get($this->api . '/cargos');
        $cargos = $cargosResponse->successful()
            ? ($cargosResponse->json()['data'] ?? $cargosResponse->json() ?? [])
            : [];

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