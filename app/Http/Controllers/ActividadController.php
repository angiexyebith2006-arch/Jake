<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActividadController extends Controller
{
    protected string $apiUrlActividades = 'http://127.0.0.1:8001/actividades/api/actividades';

    public function index()
    {
        try {
            $response = Http::get($this->apiUrlActividades);

            if ($response->successful()) {
                $actividades = $response->json()['data'] ?? [];
            } else {
                $actividades = [];
            }
        } catch (\Exception $e) {
            $actividades = [];
        }

        return view('programacion.actividad.index', compact('actividades'));
    }

    public function edit($id)
{
    return view('programacion.actividad.edit', compact('id'));
}

    public function create()
    {
        return view('programacion.actividad.create');
    }
    public function store(Request $request)
    {
        $data = [
            'id_ministerio' => 1,
            'nombre_actividad' => $request->nombre_actividad,
            'descripcion' => $request->descripcion,
            'hora_inicio' => $request->hora_inicio . ':00',
            'hora_fin' => $request->hora_fin . ':00',
        ];

        $response = Http::post(
            'http://127.0.0.1:8001/actividades/api/actividades/crear/',
            $data
        );

        if ($response->successful()) {
            return redirect()
                ->route('actividades.index')
                ->with('success', 'Actividad creada correctamente');
        }

        return back()
            ->withInput()
            ->with('error', 'Error al crear la actividad');
    }

}


   
    