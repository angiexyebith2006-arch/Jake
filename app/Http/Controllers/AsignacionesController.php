<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Usuario;
use App\Models\Ministerio;
use App\Models\Rol;
use Illuminate\Http\Request;

class AsignacionesController extends Controller
{
    /**
     * Mostrar lista de asignaciones.
     */
    public function index()
    {
        $asignaciones = Asignacion::with(['usuario', 'ministerio', 'rol'])
            ->orderBy('fecha_asignacion', 'desc')
            ->get();
        return view('asignaciones.index', compact('asignaciones'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $usuarios = Usuario::where('activo', true)->get();
        $ministerios = Ministerio::all();
        $roles = Rol::all();
        
        return view('asignaciones.create', compact('usuarios', 'ministerios', 'roles'));
    }

    /**
     * Guardar nueva asignación.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_usuario' => 'required|exists:usuarios,id_usuario',
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'id_rol' => 'required|exists:roles,id_rol',
                'fecha_asignacion' => 'sometimes|date',
                'activo' => 'sometimes|boolean'
            ]);

            // Verificar asignación única
            $asignacionExistente = Asignacion::where('id_usuario', $validated['id_usuario'])
                ->where('id_ministerio', $validated['id_ministerio'])
                ->where('id_rol', $validated['id_rol'])
                ->exists();

            if ($asignacionExistente) {
                return redirect()->back()
                    ->with('error', 'Esta asignación ya existe para el usuario, ministerio y rol seleccionados.')
                    ->withInput();
            }

            Asignacion::create([
                'id_usuario' => $validated['id_usuario'],
                'id_ministerio' => $validated['id_ministerio'],
                'id_rol' => $validated['id_rol'],
                'fecha_asignacion' => $validated['fecha_asignacion'] ?? now()->format('Y-m-d'),
                'activo' => $validated['activo'] ?? true
            ]);

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la asignación: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar asignación específica.
     */
    public function show($id)
    {
        $asignacion = Asignacion::with(['usuario', 'ministerio', 'rol', 'programaciones.actividad'])->find($id);
        
        if (!$asignacion) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Asignación no encontrada.');
        }
        
        return view('asignaciones.show', compact('asignacion'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $asignacion = Asignacion::find($id);
        $usuarios = Usuario::where('activo', true)->get();
        $ministerios = Ministerio::all();
        $roles = Rol::all();
       
        if (!$asignacion) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Asignación no encontrada.');
        }
        
        return view('asignaciones.edit', compact('asignacion', 'usuarios', 'ministerios', 'roles'));
    }

    /**
     * Actualizar asignación.
     */
    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::find($id);
        
        if (!$asignacion) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Asignación no encontrada.');
        }

        try {
            $validated = $request->validate([
                'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
                'id_ministerio' => 'sometimes|exists:ministerios,id_ministerio',
                'id_rol' => 'sometimes|exists:roles,id_rol',
                'fecha_asignacion' => 'sometimes|date',
                'activo' => 'sometimes|boolean'
            ]);

            // Verificar asignación única si se cambian los campos
            if (isset($validated['id_usuario']) || isset($validated['id_ministerio']) || isset($validated['id_rol'])) {
                $userId = $validated['id_usuario'] ?? $asignacion->id_usuario;
                $ministerioId = $validated['id_ministerio'] ?? $asignacion->id_ministerio;
                $rolId = $validated['id_rol'] ?? $asignacion->id_rol;

                $asignacionExistente = Asignacion::where('id_usuario', $userId)
                    ->where('id_ministerio', $ministerioId)
                    ->where('id_rol', $rolId)
                    ->where('id_asignacion', '!=', $id)
                    ->exists();

                if ($asignacionExistente) {
                    return redirect()->back()
                        ->with('error', 'Esta combinación de usuario, ministerio y rol ya existe en otra asignación.')
                        ->withInput();
                }
            }

            $asignacion->update($validated);

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la asignación: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar asignación.
     */
    public function destroy($id)
    {
        try {
            $asignacion = Asignacion::findOrFail($id);

            // Verificar si tiene programaciones activas
            $programacionesActivas = $asignacion->programaciones()
                ->whereIn('estado', ['Programado', 'Reemplazado'])
                ->exists();

            if ($programacionesActivas) {
                return redirect()->route('asignaciones.index')
                    ->with('error', 'No se puede eliminar la asignación porque tiene programaciones activas.');
            }

            $asignacion->delete();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Desactivar asignación.
     */
    public function desactivar($id)
    {
        try {
            $asignacion = Asignacion::findOrFail($id);
            $asignacion->update(['activo' => false]);

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación desactivada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al desactivar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Activar asignación.
     */
    public function activar($id)
    {
        try {
            $asignacion = Asignacion::findOrFail($id);
            $asignacion->update(['activo' => true]);

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación activada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al activar la asignación: ' . $e->getMessage());
        }
    }
}