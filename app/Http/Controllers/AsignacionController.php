<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AsignacionController extends Controller
{
    protected string $apiUrlAsignacion = 'http://127.0.0.1:5431/api/asignaciones';

    public function index()
    {
        try {
            $response = Http::get($this->apiUrlAsignacion);

            if ($response->successful()) {
                $asignaciones = $response->json();
            } else {
                $asignaciones = [];
            }
        } catch (\Exception $e) {
            $asignaciones = [];
        }

        return view('perfil.asignacion.index', compact('asignaciones'));
    }

    public function edit($id)
    {
        return view('perfil.asignacion.edit', compact('id'));
    }

    public function create()
    {
        return view('perfil.asignacion.create');
    }

}