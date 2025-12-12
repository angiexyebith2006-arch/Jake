<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

class ProgramacionesController extends Controller
{
    protected $javaApiService;

    public function __construct(JavaApiService $javaApiService)
    {
        $this->javaApiService = $javaApiService;
    }

    // ======================================================
    // LISTAR PROGRAMACIONES
    // ======================================================
public function index(Request $request)
{
    $respuesta = $this->javaApiService->getProgramaciones();

    if (!$respuesta['success']) {
        return back()->with('error', 'Error al obtener programaciones');
    }

    // Tomar SOLO la lista real
    $lista = $respuesta['data']['data'] ?? [];

    // Convertir cada item en objeto
    $programaciones = collect($lista)->map(function ($item) {
        return (object) $item;
    });

    return view('programacion.index', compact('programaciones'));
}


    // ======================================================
    // FORMULARIO DE CREACIÓN
    // ======================================================
    public function create()
    {
        $actividades = Http::get('http://localhost:5431/api/actividades')->json()['data'] ?? [];
        $asignaciones = Http::get('http://localhost:5431/api/asignaciones')->json()['data'] ?? [];

        return view('programacion.create', compact('actividades', 'asignaciones'));
    }

    // ======================================================
    // GUARDAR PROGRAMACIÓN
    // ======================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_actividad' => 'required',
            'id_asignacion' => 'required',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'estado' => 'nullable'
        ]);

        $response = Http::post($this->apiUrl, [
            "actividad" => [
                "idActividad" => $request->id_actividad
            ],
            "asignacion" => [
                "idAsignacion" => $request->id_asignacion
            ],
            "fecha" => $request->fecha,
            "horaInicio" => $request->hora_inicio,
            "horaFin" => $request->hora_fin,
            "estado" => $request->estado
        ]);

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'Error al crear la programación');
        }

        return redirect()->route('programacion.index')
            ->with('success', 'Programación creada correctamente');
    }

    // ======================================================
    // EDITAR
    // ======================================================
    public function edit($id)
    {
        $response = Http::get("$this->apiUrl/$id");

        if ($response->failed()) {
            return back()->with('error', 'Error al obtener la programación');
        }

        $programacion = $response->json()['data'] ?? null;

        if (!$programacion) {
            return back()->with('error', 'Programación no encontrada');
        }

        $actividades = Http::get('http://localhost:5431/api/actividades')->json()['data'] ?? [];
        $asignaciones = Http::get('http://localhost:5431/api/asignaciones')->json()['data'] ?? [];

        return view('programacion.edit', compact('programacion', 'actividades', 'asignaciones'));
    }

    // ======================================================
    // ACTUALIZAR
    // ======================================================
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_actividad' => 'required',
            'id_asignacion' => 'required',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'estado' => 'nullable'
        ]);

        $response = Http::put("$this->apiUrl/$id", [
            "actividad" => [
                "idActividad" => $request->id_actividad
            ],
            "asignacion" => [
                "idAsignacion" => $request->id_asignacion
            ],
            "fecha" => $request->fecha,
            "horaInicio" => $request->hora_inicio,
            "horaFin" => $request->hora_fin,
            "estado" => $request->estado
        ]);

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'Error al actualizar la programación');
        }

        return redirect()->route('programacion.index')
            ->with('success', 'Programación actualizada correctamente');
    }

    // ======================================================
    // ELIMINAR
    // ======================================================
    public function destroy($id)
    {
        $response = Http::delete("$this->apiUrl/$id");

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'No se pudo eliminar la programación');
        }

        return redirect()->route('programacion.index')
            ->with('success', 'Programación eliminada correctamente');
    }

    // ======================================================
    // CONFIRMAR PROGRAMACIÓN
    // ======================================================
    public function confirmar($id)
    {
        $response = Http::put("$this->apiUrl/$id/confirmar");

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'No se pudo confirmar');
        }

        return back()->with('success', 'Programación confirmada');
    }

    // ======================================================
    // CANCELAR CONFIRMACIÓN
    // ======================================================
    public function cancelar($id)
    {
        $response = Http::put("$this->apiUrl/$id/cancelar");

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'No se pudo cancelar la confirmación');
        }

        return back()->with('success', 'Confirmación cancelada');
    }

    // ======================================================
    // MARCAR COMO REEMPLAZADA
    // ======================================================
    public function reemplazar($id)
    {
        $response = Http::put("$this->apiUrl/$id/reemplazada");

        if ($response->failed()) {
            return back()->with('error', $response->json()['mensaje'] ?? 'No se pudo marcar como reemplazada');
        }

        return back()->with('success', 'Programación marcada como reemplazada');
    }
}
