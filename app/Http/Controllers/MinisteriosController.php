<?php

namespace App\Http\Controllers;

use App\Models\Ministerio;
use Illuminate\Http\Request;

class MinisteriosController extends Controller
{
  
    public function index()
    {
        $ministerios = Ministerio::orderBy('nombre_ministerio')->get();
        return view('ministerios.index', compact('ministerios'));
    }

    
    public function create()
    {
        return view('ministerios.create');
    }

   
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_ministerio' => 'required|string|max:100|unique:ministerios,nombre_ministerio',
                'descripcion' => 'nullable|string|max:200'
            ]);

            Ministerio::create($validated);

            return redirect()->route('ministerios.index')
                ->with('success', 'Ministerio creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el ministerio: ' . $e->getMessage())
                ->withInput();
        }
    }

   
    public function show($id)
    {
        $ministerio = Ministerio::with(['asignaciones.usuario', 'asignaciones.rol', 'actividades'])->find($id);
        
        if (!$ministerio) {
            return redirect()->route('ministerios.index')
                ->with('error', 'Ministerio no encontrado.');
        }
        
        return view('ministerios.show', compact('ministerio'));
    }

    public function edit($id)
    {
        $ministerio = Ministerio::find($id);
       
        if (!$ministerio) {
            return redirect()->route('ministerios.index')
                ->with('error', 'Ministerio no encontrado.');
        }
        
        return view('ministerios.edit', compact('ministerio'));
    }

  
    public function update(Request $request, $id)
    {
        $ministerio = Ministerio::find($id);
        
        if (!$ministerio) {
            return redirect()->route('ministerios.index')
                ->with('error', 'Ministerio no encontrado.');
        }

        try {
            $validated = $request->validate([
                'nombre_ministerio' => 'required|string|max:100|unique:ministerios,nombre_ministerio,' . $id . ',id_ministerio',
                'descripcion' => 'nullable|string|max:200'
            ]);

            $ministerio->update($validated);

            return redirect()->route('ministerios.index')
                ->with('success', 'Ministerio actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el ministerio: ' . $e->getMessage())
                ->withInput();
        }
    }

   
    public function destroy($id)
    {
        try {
            $ministerio = Ministerio::findOrFail($id);

  
            if ($ministerio->asignaciones()->where('activo', true)->exists()) {
                return redirect()->route('ministerios.index')
                    ->with('error', 'No se puede eliminar el ministerio porque tiene asignaciones activas.');
            }

            $ministerio->delete();

            return redirect()->route('ministerios.index')
                ->with('success', 'Ministerio eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('ministerios.index')
                ->with('error', 'Error al eliminar el ministerio: ' . $e->getMessage());
        }
    }
}