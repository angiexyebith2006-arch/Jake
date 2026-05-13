<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VistaController extends Controller
{
    private $api = 'http://127.0.0.1:5431/api/vistas';

    // LISTAR
    public function index()
    {
        $response = Http::get($this->api);

        $vistas = $response->json();

        return view('perfil.permiso.vista.index', compact('vistas'));
    }

    // VISTA CREAR
    public function crear()
    {
        return view('perfil.permiso.vista.create');
    }

    // GUARDAR
    public function guardar(Request $request)
    {
        Http::post($this->api, [
            'nombre' => $request->nombre
        ]);

        return redirect()
            ->route('vistas.index')
            ->with('success', 'Vista creada correctamente');
    }

    // VISTA EDITAR
    public function editar($id)
    {
        $response = Http::get($this->api . '/' . $id);

        $vista = $response->json();

        return view('perfil.permiso.vista.edit', compact('vista'));
    }

    // ACTUALIZAR
    public function actualizar(Request $request, $id)
    {
        Http::put($this->api . '/' . $id, [
            'nombre' => $request->nombre
        ]);

        return redirect()
            ->route('vistas.index')
            ->with('success', 'Vista actualizada correctamente');
    }

    // ELIMINAR
    public function eliminar($id)
    {
        Http::delete($this->api . '/' . $id);

        return redirect()
            ->route('vistas.index')
            ->with('success', 'Vista eliminada correctamente');
    }
}