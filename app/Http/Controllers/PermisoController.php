<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Funcion;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    // Listar permisos
    public function index()
    {
        return Permiso::all();
    }

    // Crear permiso
    public function store(Request $request)
    {
        $request->validate([
            'nombre_permiso' => 'required|string|max:100|unique:permisos,nombre_permiso',
            'descripcion' => 'nullable|string'
        ]);

        return Permiso::create($request->all());
    }

    // Actualizar permiso
    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);

        $request->validate([
            'nombre_permiso' => 'required|string|max:100|unique:permisos,nombre_permiso,' . $id . ',id_permiso',
            'descripcion' => 'nullable|string'
        ]);

        $permiso->update($request->all());

        return $permiso;
    }

    // Eliminar permiso
    public function destroy($id)
    {
        Permiso::destroy($id);
        return response()->json(['mensaje' => 'Permiso eliminado']);
    }

    // Asignar permisos a una función (rol)
    public function asignarPermisos(Request $request, $id_funcion)
    {
        $funcion = Funcion::findOrFail($id_funcion);

        $request->validate([
            'permisos' => 'required|array'
        ]);

        $funcion->permisos()->sync($request->permisos);

        return response()->json(['mensaje' => 'Permisos asignados']);
    }
}
