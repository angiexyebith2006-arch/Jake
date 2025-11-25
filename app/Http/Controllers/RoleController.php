<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Mostrar lista de roles.
     */
    public function index()
    {
        $roles = Rol::orderBy('nombre_rol')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Guardar nuevo rol.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_rol' => 'required|string|max:100|unique:roles,nombre_rol',
                'descripcion' => 'nullable|string|max:200'
            ]);

            Rol::create($validated);

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar rol específico.
     */
    public function show($id)
    {
        $rol = Rol::with(['asignaciones.usuario', 'asignaciones.ministerio'])->find($id);
        
        if (!$rol) {
            return redirect()->route('roles.index')
                ->with('error', 'Rol no encontrado.');
        }
        
        return view('roles.show', compact('rol'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $rol = Rol::find($id);
       
        if (!$rol) {
            return redirect()->route('roles.index')
                ->with('error', 'Rol no encontrado.');
        }
        
        return view('roles.edit', compact('rol'));
    }

    /**
     * Actualizar rol.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);
        
        if (!$rol) {
            return redirect()->route('roles.index')
                ->with('error', 'Rol no encontrado.');
        }

        try {
            $validated = $request->validate([
                'nombre_rol' => 'required|string|max:100|unique:roles,nombre_rol,' . $id . ',id_rol',
                'descripcion' => 'nullable|string|max:200'
            ]);

            $rol->update($validated);

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar rol.
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);

            // Verificar si tiene asignaciones activas
            if ($rol->asignaciones()->where('activo', true)->exists()) {
                return redirect()->route('roles.index')
                    ->with('error', 'No se puede eliminar el rol porque tiene asignaciones activas.');
            }

            $rol->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }
}