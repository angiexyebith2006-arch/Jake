<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Ministerio;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    /**
     * Mostrar lista de actividades.
     */
    public function index()
    {
        $actividades = Actividad::with(['ministerio'])
            ->orderBy('nombre_actividad')
            ->get();
        return view('actividades.index', compact('actividades'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $ministerios = Ministerio::all();
        return view('actividades.create', compact('ministerios'));
    }

    /**
     * Guardar nueva actividad.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'nombre_actividad' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:200'
            ]);

            Actividad::create($validated);

            return redirect()->route('actividades.index')
                ->with('success', 'Actividad creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar actividad específica.
     */
    public function show($id)
    {
        $actividad = Actividad::with(['ministerio', 'programaciones.asignacion.usuario'])->find($id);
        
        if (!$actividad) {
            return redirect()->route('actividades.index')
                ->with('error', 'Actividad no encontrada.');
        }
        
        return view('actividades.show', compact('actividad'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $actividad = Actividad::find($id);
        $ministerios = Ministerio::all();
       
        if (!$actividad) {
            return redirect()->route('actividades.index')
                ->with('error', 'Actividad no encontrada.');
        }
        
        return view('actividades.edit', compact('actividad', 'ministerios'));
    }

    /**
     * Actualizar actividad.
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividad::find($id);
        
        if (!$actividad) {
            return redirect()->route('actividades.index')
                ->with('error', 'Actividad no encontrada.');
        }

        try {
            $validated = $request->validate([
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'nombre_actividad' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:200'
            ]);

            $actividad->update($validated);

            return redirect()->route('actividades.index')
                ->with('success', 'Actividad actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar actividad.
     */
    public function destroy($id)
    {
        try {
            $actividad = Actividad::findOrFail($id);

            // Verificar si tiene programaciones
            if ($actividad->programaciones()->exists()) {
                return redirect()->route('actividades.index')
                    ->with('error', 'No se puede eliminar la actividad porque tiene programaciones asociadas.');
            }

            $actividad->delete();

            return redirect()->route('actividades.index')
                ->with('success', 'Actividad eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('actividades.index')
                ->with('error', 'Error al eliminar la actividad: ' . $e->getMessage());
        }
    }
}