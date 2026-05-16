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
            'tipo_finanza' => 'required',
        ]);

        CategoriaFinanza::create([
            'nombre_categoria' => $request->nombre_categoria,
            'tipo_finanza' => $request->tipo_finanza,
            'descripcion' => $request->descripcion,
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
        $categoria = CategoriaFinanza::findOrFail($id);

        $categoria->update([
            'nombre_categoria' => $request->nombre_categoria,
            'tipo_finanza' => $request->tipo_finanza,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.index');
    }

    public function destroy($id)
    {
        $categoria = CategoriaFinanza::findOrFail($id);

        $categoria->delete();

        return redirect()->route('categorias.index');
    }
}