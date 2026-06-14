<?php

namespace App\Http\Controllers;

use App\Models\CategoriaFinanza;
use Illuminate\Http\Request;

class CategoriasFinanzaController extends Controller
{
    public function index()
    {
        $categorias = CategoriaFinanza::all();

        return view('finanzas.categoria.index', compact('categorias'));
    }

    public function create()
    {
        return view('finanzas.categoria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required',
            'tipo_finanza'     => 'required|in:Ingreso,Egreso',
        ]);

        CategoriaFinanza::create([
            'nombre_categoria' => $request->nombre_categoria,
            'tipo_finanza'     => $request->tipo_finanza,
            'descripcion'      => $request->descripcion,
        ]);

        return redirect()->route('categorias.index');
    }

    public function edit($id)
    {
        $categoria = CategoriaFinanza::findOrFail($id);

        return view('finanzas.categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_categoria' => 'required',
            'tipo_finanza'     => 'required|in:Ingreso,Egreso',
        ]);

        $categoria = CategoriaFinanza::findOrFail($id);

        $categoria->update([
            'nombre_categoria' => $request->nombre_categoria,
            'tipo_finanza'     => $request->tipo_finanza,
            'descripcion'      => $request->descripcion,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        $categoria = CategoriaFinanza::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index');
    }
}