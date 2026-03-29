<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoriaFinanza;
use Illuminate\Http\Request;

class CategoriasFinanzasController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaFinanza::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_categoria' => 'required|string|max:255',
            'tipo_finanza' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = CategoriaFinanza::create($validated);

        return response()->json($categoria, 201);
    }

    public function show($id)
    {
        $categoria = CategoriaFinanza::with('finanzas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $categoria
        ]);
    }

    public function update(Request $request, $id)
    {
        $categoria = CategoriaFinanza::findOrFail($id);

        $validated = $request->validate([
            'nombre_categoria' => 'sometimes|string|max:255',
            'tipo_finanza' => 'sometimes|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $categoria->update($validated);

        return response()->json($categoria);
    }

    public function destroy($id)
    {
        $categoria = CategoriaFinanza::findOrFail($id);
        $categoria->delete();

        return response()->json([
            'mensaje' => 'Categoria eliminada correctamente'
        ]);
    }
}