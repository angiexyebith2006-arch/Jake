<?php

namespace App\Http\Controllers;

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

}