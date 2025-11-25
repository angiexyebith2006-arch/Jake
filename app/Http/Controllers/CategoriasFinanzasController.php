<?php

namespace App\Http\Controllers;

use App\Models\CategoriaFinanza;
use Illuminate\Http\Request;

class CategoriasFinanzasController extends Controller
{
    /**
     * Mostrar lista de categorías financieras.
     */
    public function index()
    {
        $categorias = CategoriaFinanza::orderBy('tipo')
            ->orderBy('nombre_categoria')
            ->get();
        return view('categorias-finanzas.index', compact('categorias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('categorias-finanzas.create');
    }

    /**
     * Guardar nueva categoría financiera.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_categoria' => 'required|string|max:100|unique:categorias_finanzas,nombre_categoria',
                'tipo' => 'required|in:Ingreso,Egreso',
                'descripcion' => 'nullable|string|max:200'
            ]);

            CategoriaFinanza::create($validated);

            return redirect()->route('categorias-finanzas.index')
                ->with('success', 'Categoría financiera creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar categoría específica.
     */
    public function show($id)
    {
        $categoria = CategoriaFinanza::with(['finanzas.ministerio'])->find($id);
        
        if (!$categoria) {
            return redirect()->route('categorias-finanzas.index')
                ->with('error', 'Categoría financiera no encontrada.');
        }
        
        return view('categorias-finanzas.show', compact('categoria'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $categoria = CategoriaFinanza::find($id);
       
        if (!$categoria) {
            return redirect()->route('categorias-finanzas.index')
                ->with('error', 'Categoría financiera no encontrada.');
        }
        
        return view('categorias-finanzas.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría financiera.
     */
    public function update(Request $request, $id)
    {
        $categoria = CategoriaFinanza::find($id);
        
        if (!$categoria) {
            return redirect()->route('categorias-finanzas.index')
                ->with('error', 'Categoría financiera no encontrada.');
        }

        try {
            $validated = $request->validate([
                'nombre_categoria' => 'required|string|max:100|unique:categorias_finanzas,nombre_categoria,' . $id . ',id_categoria',
                'tipo' => 'required|in:Ingreso,Egreso',
                'descripcion' => 'nullable|string|max:200'
            ]);

            $categoria->update($validated);

            return redirect()->route('categorias-finanzas.index')
                ->with('success', 'Categoría financiera actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar categoría financiera.
     */
    public function destroy($id)
    {
        try {
            $categoria = CategoriaFinanza::findOrFail($id);

            // Verificar si tiene movimientos asociados
            if ($categoria->finanzas()->exists()) {
                return redirect()->route('categorias-finanzas.index')
                    ->with('error', 'No se puede eliminar la categoría porque tiene movimientos financieros asociados.');
            }

            $categoria->delete();

            return redirect()->route('categorias-finanzas.index')
                ->with('success', 'Categoría financiera eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('categorias-finanzas.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}