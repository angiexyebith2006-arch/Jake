<?php

namespace App\Http\Controllers;

// ✅ ESTA LÍNEA ES LA QUE FALTABA
use App\Http\Controllers\Controller;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Mostrar lista de usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('nombre')->get();
        return view('perfil.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('perfil.create');
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:100|unique:usuarios,correo',
            'telefono' => 'nullable|string|max:20',
            'rol'   => 'sometimes|string',
            'nivel_ministerial'   => 'sometimes|string'
        ]);

        Usuario::create($validated);

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('perfil.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:100|unique:usuarios,correo,' . $id . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
            'activo'   => 'sometimes|boolean',
            'Rol'   => 'sometimes|boolean',
            'Nivel Ministerial'   => 'sometimes|boolean'
        ]);

        $usuario->update($validated);

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
