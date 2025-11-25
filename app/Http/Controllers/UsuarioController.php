<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Mostrar lista de usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('nombre')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'correo' => 'required|email|max:100|unique:usuarios,correo',
                'telefono' => 'nullable|string|max:20',
                'activo' => 'sometimes|boolean'
            ]);

            Usuario::create($validated);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar usuario específico.
     */
    public function show($id)
    {
        $usuario = Usuario::with(['asignaciones.ministerio', 'asignaciones.rol'])->find($id);
        
        if (!$usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $usuario = Usuario::find($id);
       
        if (!$usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Usuario no encontrado.');
        }

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'correo' => 'required|email|max:100|unique:usuarios,correo,' . $id . ',id_usuario',
                'telefono' => 'nullable|string|max:20',
                'activo' => 'sometimes|boolean'
            ]);

            $usuario->update($validated);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar usuario.
     */
    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Verificar si tiene asignaciones activas
            if ($usuario->asignaciones()->where('activo', true)->exists()) {
                return redirect()->route('usuarios.index')
                    ->with('error', 'No se puede eliminar el usuario porque tiene asignaciones activas.');
            }

            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Desactivar usuario.
     */
    public function desactivar($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->update(['activo' => false]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario desactivado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al desactivar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Activar usuario.
     */
    public function activar($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->update(['activo' => true]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario activado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al activar el usuario: ' . $e->getMessage());
        }
    }
}