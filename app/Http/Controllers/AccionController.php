<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccionController extends Controller
{
    private $api = 'http://127.0.0.1:5431/api/acciones';

    // LISTAR
    public function index()
    {
        $response = Http::get($this->api);

        $acciones = $response->json()['data'];

        return view('perfil.permiso.accion.index', compact('acciones'));
    }

    // VISTA CREAR
    public function crear()
    {
        return view('perfil.permiso.accion.create');
    }

    // GUARDAR
    public function guardar(Request $request)
    {
        Http::post($this->api, [
            'nombreAccion' => $request->nombreAccion
        ]);

        return redirect()->route('acciones.index')
        ->with('success', 'Acción creada correctamente');
    }

    // VISTA EDITAR
    public function editar($id)
    {
        $response = Http::get($this->api . '/' . $id);

        $accion = $response->json()['data'];

        return view('perfil.permiso.accion.edit', compact('accion'));
    }

    // ACTUALIZAR
    public function actualizar(Request $request, $id)
    {
        Http::put($this->api . '/' . $id, [
            'nombreAccion' => $request->nombreAccion
        ]);

        return redirect()->route('acciones.index')
        ->with('success', 'Acción actualizada correctamente');
    }

    // ELIMINAR
    public function eliminar($id)
    {
        Http::delete($this->api . '/' . $id);

        return redirect()->route('acciones.index')
        ->with('success', 'Acción eliminada correctamente');
    }
}