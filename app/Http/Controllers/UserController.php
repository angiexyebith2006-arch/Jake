<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use App\Models\Ministerio;
use App\Models\Rol;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios.
     */
    public function index(Request $request)
    {
        $query = Usuario::query();

        // Filtro por estado activo/inactivo
        if ($request->has('activo') && $request->activo != '') {
            $query->where('activo', $request->activo);
        }

        // Búsqueda por nombre o correo
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        $usuarios = $query->orderBy('nombre')
                         ->get();

        return view('users.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $ministerios = Ministerio::all();
        $roles = Rol::all();
        
        return view('users.create', compact('ministerios', 'roles'));
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

            // Crear el usuario
            $usuario = Usuario::create($validated);

            // Asignar ministerios y roles si se proporcionaron
            if ($request->has('asignaciones')) {
                foreach ($request->asignaciones as $asignacion) {
                    Asignacion::create([
                        'id_usuario' => $usuario->id_usuario,
                        'id_ministerio' => $asignacion['id_ministerio'],
                        'id_rol' => $asignacion['id_rol'],
                        'fecha_asignacion' => now()->format('Y-m-d'),
                        'activo' => true
                    ]);
                }
            }

            return redirect()->route('users.index')
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
        $usuario = Usuario::with([
            'asignaciones.ministerio', 
            'asignaciones.rol',
            'asignaciones.programaciones.actividad'
        ])->find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }

        // Obtener estadísticas del usuario
        $estadisticas = [
            'total_asignaciones' => $usuario->asignaciones->count(),
            'asignaciones_activas' => $usuario->asignaciones->where('activo', true)->count(),
            'programaciones_futuras' => $usuario->asignaciones->flatMap(function ($asignacion) {
                return $asignacion->programaciones->where('fecha', '>=', now()->format('Y-m-d'));
            })->count(),
            'total_reemplazos' => $usuario->asignaciones->flatMap(function ($asignacion) {
                return $asignacion->reemplazosSolicitados->merge($asignacion->reemplazosRealizados);
            })->count()
        ];
        
        return view('users.show', compact('usuario', 'estadisticas'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $usuario = Usuario::with(['asignaciones.ministerio', 'asignaciones.rol'])->find($id);
        $ministerios = Ministerio::all();
        $roles = Rol::all();
       
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('users.edit', compact('usuario', 'ministerios', 'roles'));
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
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

            return redirect()->route('users.index')
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
                return redirect()->route('users.index')
                    ->with('error', 'No se puede eliminar el usuario porque tiene asignaciones activas.');
            }

            $usuario->delete();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('users.index')
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

            // También desactivar todas sus asignaciones
            $usuario->asignaciones()->update(['activo' => false]);

            return redirect()->route('users.index')
                ->with('success', 'Usuario desactivado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('users.index')
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

            return redirect()->route('users.index')
                ->with('success', 'Usuario activado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al activar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar perfil del usuario.
     */
    public function perfil($id)
    {
        $usuario = Usuario::with([
            'asignaciones.ministerio', 
            'asignaciones.rol',
            'asignaciones.programaciones' => function($query) {
                $query->where('fecha', '>=', now()->format('Y-m-d'))
                      ->where('estado', 'Programado')
                      ->with('actividad');
            }
        ])->find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('users.perfil', compact('usuario'));
    }

    /**
     * Mostrar programaciones del usuario.
     */
    public function programaciones($id, Request $request)
    {
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }

        $query = $usuario->programaciones()->with(['actividad.ministerio', 'asignacion.rol']);

        // Filtros
        if ($request->has('fecha') && $request->fecha != '') {
            $query->where('fecha', $request->fecha);
        } else {
            $query->where('fecha', '>=', now()->format('Y-m-d'));
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $programaciones = $query->orderBy('fecha')
                               ->orderBy('hora_inicio')
                               ->get();

        return view('users.programaciones', compact('usuario', 'programaciones'));
    }

    /**
     * Mostrar asignaciones del usuario.
     */
    public function asignaciones($id)
    {
        $usuario = Usuario::with(['asignaciones.ministerio', 'asignaciones.rol'])->find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('users.asignaciones', compact('usuario'));
    }

    /**
     * Mostrar historial de reemplazos del usuario.
     */
    public function reemplazos($id)
    {
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }

        // Reemplazos donde el usuario fue reemplazado
        $reemplazosSolicitados = $usuario->reemplazosSolicitados()
            ->with([
                'programacion.actividad.ministerio',
                'reemplazoPor.usuario',
                'autorizaciones.autorizador'
            ])
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        // Reemplazos donde el usuario reemplazó a alguien
        $reemplazosRealizados = $usuario->reemplazosRealizados()
            ->with([
                'programacion.actividad.ministerio',
                'reemplazado.usuario',
                'autorizaciones.autorizador'
            ])
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return view('users.reemplazos', compact('usuario', 'reemplazosSolicitados', 'reemplazosRealizados'));
    }

    /**
     * Mostrar formulario para agregar asignación a usuario.
     */
    public function agregarAsignacion($id)
    {
        $usuario = Usuario::find($id);
        $ministerios = Ministerio::all();
        $roles = Rol::all();
       
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        return view('users.agregar-asignacion', compact('usuario', 'ministerios', 'roles'));
    }

    /**
     * Guardar nueva asignación para el usuario.
     */
    public function storeAsignacion(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $validated = $request->validate([
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'id_rol' => 'required|exists:roles,id_rol'
            ]);

            // Verificar que no existe ya esta asignación
            $asignacionExistente = Asignacion::where('id_usuario', $id)
                ->where('id_ministerio', $validated['id_ministerio'])
                ->where('id_rol', $validated['id_rol'])
                ->exists();

            if ($asignacionExistente) {
                return redirect()->back()
                    ->with('error', 'El usuario ya tiene esta asignación.')
                    ->withInput();
            }

            Asignacion::create([
                'id_usuario' => $id,
                'id_ministerio' => $validated['id_ministerio'],
                'id_rol' => $validated['id_rol'],
                'fecha_asignacion' => now()->format('Y-m-d'),
                'activo' => true
            ]);

            return redirect()->route('users.asignaciones', $id)
                ->with('success', 'Asignación agregada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al agregar la asignación: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar dashboard del usuario.
     */
    public function dashboard($id)
    {
        $usuario = Usuario::with([
            'asignaciones.ministerio',
            'asignaciones.rol',
            'asignaciones.programaciones' => function($query) {
                $query->where('fecha', '>=', now()->format('Y-m-d'))
                      ->where('estado', 'Programado')
                      ->with('actividad')
                      ->orderBy('fecha')
                      ->orderBy('hora_inicio')
                      ->take(5);
            }
        ])->find($id);
        
        if (!$usuario) {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado.');
        }

        // Estadísticas para el dashboard
        $estadisticas = [
            'programaciones_proximas' => $usuario->programaciones()
                ->where('fecha', '>=', now()->format('Y-m-d'))
                ->where('estado', 'Programado')
                ->count(),
            'asignaciones_activas' => $usuario->asignaciones()->where('activo', true)->count(),
            'reemplazos_pendientes' => $usuario->reemplazosSolicitados()
                ->where('estado', 'Pendiente')
                ->count(),
            'total_ministerios' => $usuario->asignaciones()
                ->where('activo', true)
                ->distinct('id_ministerio')
                ->count('id_ministerio')
        ];

        return view('users.dashboard', compact('usuario', 'estadisticas'));
    }

    /**
     * Buscar usuarios (para AJAX requests).
     */
    public function buscar(Request $request)
    {
        $query = Usuario::query();

        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        if ($request->has('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        $usuarios = $query->orderBy('nombre')
                         ->limit(10)
                         ->get(['id_usuario', 'nombre', 'correo', 'activo']);

        return response()->json($usuarios);
    }
}