<?php

namespace App\Http\Controllers;

use App\Models\Reemplazo;
use App\Models\Programacion;
use App\Models\Asignacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReemplazosController extends Controller
{
    /**
     * Mostrar lista de reemplazos.
     */
    public function index(Request $request)
    {
        $query = Reemplazo::with([
            'programacion.actividad.ministerio',
            'reemplazado.usuario',
            'reemplazoPor.usuario',
            'autorizaciones.autorizador'
        ]);

        // Filtros
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->whereHas('programacion', function($q) use ($request) {
                $q->where('fecha', '>=', $request->fecha_inicio);
            });
        }

        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $query->whereHas('programacion', function($q) use ($request) {
                $q->where('fecha', '<=', $request->fecha_fin);
            });
        }

        $reemplazos = $query->orderBy('fecha_solicitud', 'desc')
                           ->orderBy('id_reemplazo', 'desc')
                           ->get();

        return view('reemplazos.index', compact('reemplazos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(Request $request)
    {
        // Si viene de una programación específica
        $idProgramacion = $request->get('programacion_id');
        
        $programaciones = Programacion::with(['actividad.ministerio', 'asignacion.usuario'])
            ->where('estado', 'Programado')
            ->where('fecha', '>=', now()->format('Y-m-d'))
            ->get();

        $asignaciones = Asignacion::with(['usuario', 'ministerio', 'rol'])
            ->where('activo', true)
            ->get();

        $programacionSeleccionada = null;
        if ($idProgramacion) {
            $programacionSeleccionada = Programacion::with(['asignacion'])->find($idProgramacion);
        }

        return view('reemplazos.create', compact('programaciones', 'asignaciones', 'programacionSeleccionada'));
    }

    /**
     * Guardar nueva solicitud de reemplazo.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_programacion' => 'required|exists:programaciones,id_programacion',
                'id_asignacion_reemplazado' => 'required|exists:asignaciones,id_asignacion',
                'id_asignacion_reemplazo_por' => 'required|exists:asignaciones,id_asignacion',
                'motivo' => 'required|string|max:200'
            ]);

            // Verificar que la programación existe y está programada
            $programacion = Programacion::findOrFail($validated['id_programacion']);
            if ($programacion->estado !== 'Programado') {
                return redirect()->back()
                    ->with('error', 'No se puede solicitar reemplazo para una programación cancelada o reemplazada.')
                    ->withInput();
            }

            // Verificar que el reemplazado es quien está programado
            if ($programacion->id_asignacion != $validated['id_asignacion_reemplazado']) {
                return redirect()->back()
                    ->with('error', 'El servidor a reemplazar no coincide con la programación.')
                    ->withInput();
            }

            // Verificar que no existe un reemplazo pendiente para esta programación
            $reemplazoExistente = Reemplazo::where('id_programacion', $validated['id_programacion'])
                ->where('estado', 'Pendiente')
                ->exists();

            if ($reemplazoExistente) {
                return redirect()->back()
                    ->with('error', 'Ya existe una solicitud de reemplazo pendiente para esta programación.')
                    ->withInput();
            }

            // Verificar que el reemplazo es del mismo ministerio
            $reemplazado = Asignacion::find($validated['id_asignacion_reemplazado']);
            $reemplazoPor = Asignacion::find($validated['id_asignacion_reemplazo_por']);

            if ($reemplazado->id_ministerio != $reemplazoPor->id_ministerio) {
                return redirect()->back()
                    ->with('error', 'El servidor de reemplazo debe pertenecer al mismo ministerio.')
                    ->withInput();
            }

            // Crear el reemplazo
            $reemplazo = Reemplazo::create([
                'id_programacion' => $validated['id_programacion'],
                'id_asignacion_reemplazado' => $validated['id_asignacion_reemplazado'],
                'id_asignacion_reemplazo_por' => $validated['id_asignacion_reemplazo_por'],
                'motivo' => $validated['motivo'],
                'fecha_solicitud' => now()->format('Y-m-d'),
                'estado' => 'Pendiente'
            ]);

            // Cambiar estado de la programación a "Reemplazado"
            $programacion->update(['estado' => 'Reemplazado']);

            return redirect()->route('reemplazos.index')
                ->with('success', 'Solicitud de reemplazo creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la solicitud de reemplazo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar reemplazo específico.
     */
    public function show($id)
    {
        $reemplazo = Reemplazo::with([
            'programacion.actividad.ministerio',
            'reemplazado.usuario',
            'reemplazado.rol',
            'reemplazoPor.usuario',
            'reemplazoPor.rol',
            'autorizaciones.autorizador'
        ])->find($id);
        
        if (!$reemplazo) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Reemplazo no encontrado.');
        }
        
        return view('reemplazos.show', compact('reemplazo'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $reemplazo = Reemplazo::with([
            'programacion',
            'reemplazado',
            'reemplazoPor'
        ])->find($id);
       
        if (!$reemplazo) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Reemplazo no encontrado.');
        }

        // Solo permitir editar reemplazos pendientes
        if ($reemplazo->estado !== 'Pendiente') {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Solo se pueden editar reemplazos pendientes.');
        }

        $asignaciones = Asignacion::with(['usuario', 'rol'])
            ->where('activo', true)
            ->where('id_ministerio', $reemplazo->reemplazado->id_ministerio)
            ->get();

        return view('reemplazos.edit', compact('reemplazo', 'asignaciones'));
    }

    /**
     * Actualizar reemplazo.
     */
    public function update(Request $request, $id)
    {
        $reemplazo = Reemplazo::find($id);
        
        if (!$reemplazo) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Reemplazo no encontrado.');
        }

        // Solo permitir actualizar reemplazos pendientes
        if ($reemplazo->estado !== 'Pendiente') {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Solo se pueden modificar reemplazos pendientes.');
        }

        try {
            $validated = $request->validate([
                'id_asignacion_reemplazo_por' => 'required|exists:asignaciones,id_asignacion',
                'motivo' => 'required|string|max:200'
            ]);

            // Verificar que el reemplazo es del mismo ministerio
            $reemplazado = $reemplazo->reemplazado;
            $reemplazoPor = Asignacion::find($validated['id_asignacion_reemplazo_por']);

            if ($reemplazado->id_ministerio != $reemplazoPor->id_ministerio) {
                return redirect()->back()
                    ->with('error', 'El servidor de reemplazo debe pertenecer al mismo ministerio.')
                    ->withInput();
            }

            $reemplazo->update($validated);

            return redirect()->route('reemplazos.index')
                ->with('success', 'Reemplazo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el reemplazo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar reemplazo.
     */
    public function destroy($id)
    {
        try {
            $reemplazo = Reemplazo::findOrFail($id);

            // Solo permitir eliminar reemplazos pendientes
            if ($reemplazo->estado !== 'Pendiente') {
                return redirect()->route('reemplazos.index')
                    ->with('error', 'Solo se pueden eliminar reemplazos pendientes.');
            }

            // Restaurar el estado de la programación
            $reemplazo->programacion->update(['estado' => 'Programado']);

            $reemplazo->delete();

            return redirect()->route('reemplazos.index')
                ->with('success', 'Solicitud de reemplazo eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Error al eliminar el reemplazo: ' . $e->getMessage());
        }
    }

    /**
     * Aprobar reemplazo.
     */
    public function aprobar($id)
    {
        try {
            $reemplazo = Reemplazo::with(['programacion'])->findOrFail($id);

            if ($reemplazo->estado !== 'Pendiente') {
                return redirect()->route('reemplazos.index')
                    ->with('error', 'Solo se pueden aprobar reemplazos pendientes.');
            }

            // Actualizar estado del reemplazo
            $reemplazo->update(['estado' => 'Aprobado']);

            // Crear autorización automática si hay usuario autenticado
            if (Auth::check()) {
                $reemplazo->autorizaciones()->create([
                    'id_autorizador' => Auth::id(),
                    'fecha_autorizacion' => now()->format('Y-m-d'),
                    'observaciones' => 'Aprobado automáticamente por el sistema'
                ]);
            }

            // Actualizar la programación con el nuevo servidor
            $reemplazo->programacion->update([
                'id_asignacion' => $reemplazo->id_asignacion_reemplazo_por,
                'estado' => 'Programado'
            ]);

            return redirect()->route('reemplazos.index')
                ->with('success', 'Reemplazo aprobado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Error al aprobar el reemplazo: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar reemplazo.
     */
    public function rechazar(Request $request, $id)
    {
        try {
            $reemplazo = Reemplazo::with(['programacion'])->findOrFail($id);

            if ($reemplazo->estado !== 'Pendiente') {
                return redirect()->route('reemplazos.index')
                    ->with('error', 'Solo se pueden rechazar reemplazos pendientes.');
            }

            $validated = $request->validate([
                'observaciones' => 'required|string|max:200'
            ]);

            // Actualizar estado del reemplazo
            $reemplazo->update(['estado' => 'Rechazado']);

            // Crear autorización de rechazo si hay usuario autenticado
            if (Auth::check()) {
                $reemplazo->autorizaciones()->create([
                    'id_autorizador' => Auth::id(),
                    'fecha_autorizacion' => now()->format('Y-m-d'),
                    'observaciones' => $validated['observaciones']
                ]);
            }

            // Restaurar el estado original de la programación
            $reemplazo->programacion->update(['estado' => 'Programado']);

            return redirect()->route('reemplazos.index')
                ->with('success', 'Reemplazo rechazado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Error al rechazar el reemplazo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para autorizar reemplazo.
     */
    public function autorizar($id)
    {
        $reemplazo = Reemplazo::with([
            'programacion.actividad.ministerio',
            'reemplazado.usuario',
            'reemplazoPor.usuario'
        ])->find($id);
       
        if (!$reemplazo) {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Reemplazo no encontrado.');
        }

        if ($reemplazo->estado !== 'Pendiente') {
            return redirect()->route('reemplazos.index')
                ->with('error', 'Este reemplazo ya ha sido procesado.');
        }

        $usuarios = Usuario::where('activo', true)->get();

        return view('reemplazos.autorizar', compact('reemplazo', 'usuarios'));
    }

    /**
     * Procesar autorización de reemplazo.
     */
    public function procesarAutorizacion(Request $request, $id)
    {
        try {
            $reemplazo = Reemplazo::with(['programacion'])->findOrFail($id);

            if ($reemplazo->estado !== 'Pendiente') {
                return redirect()->route('reemplazos.index')
                    ->with('error', 'Este reemplazo ya ha sido procesado.');
            }

            $validated = $request->validate([
                'accion' => 'required|in:aprobar,rechazar',
                'id_autorizador' => 'required|exists:usuarios,id_usuario',
                'observaciones' => 'nullable|string|max:200'
            ]);

            if ($validated['accion'] === 'aprobar') {
                // Aprobar reemplazo
                $reemplazo->update(['estado' => 'Aprobado']);
                
                // Actualizar la programación con el nuevo servidor
                $reemplazo->programacion->update([
                    'id_asignacion' => $reemplazo->id_asignacion_reemplazo_por,
                    'estado' => 'Programado'
                ]);

                $mensaje = 'Reemplazo aprobado exitosamente.';
            } else {
                // Rechazar reemplazo
                $reemplazo->update(['estado' => 'Rechazado']);
                
                // Restaurar el estado original de la programación
                $reemplazo->programacion->update(['estado' => 'Programado']);

                $mensaje = 'Reemplazo rechazado exitosamente.';
            }

            // Crear registro de autorización
            $reemplazo->autorizaciones()->create([
                'id_autorizador' => $validated['id_autorizador'],
                'fecha_autorizacion' => now()->format('Y-m-d'),
                'observaciones' => $validated['observaciones']
            ]);

            return redirect()->route('reemplazos.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar la autorización: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar reemplazos pendientes.
     */
    public function pendientes()
    {
        $reemplazos = Reemplazo::with([
            'programacion.actividad.ministerio',
            'reemplazado.usuario',
            'reemplazoPor.usuario'
        ])
        ->where('estado', 'Pendiente')
        ->orderBy('fecha_solicitud', 'asc')
        ->get();

        return view('reemplazos.pendientes', compact('reemplazos'));
    }

    /**
     * Mostrar reemplazos por usuario.
     */
    public function porUsuario($idUsuario)
    {
        $reemplazos = Reemplazo::with([
            'programacion.actividad.ministerio',
            'reemplazado.usuario',
            'reemplazoPor.usuario',
            'autorizaciones.autorizador'
        ])
        ->whereHas('reemplazado', function($q) use ($idUsuario) {
            $q->where('id_usuario', $idUsuario);
        })
        ->orWhereHas('reemplazoPor', function($q) use ($idUsuario) {
            $q->where('id_usuario', $idUsuario);
        })
        ->orderBy('fecha_solicitud', 'desc')
        ->get();

        $usuario = Usuario::find($idUsuario);

        return view('reemplazos.por-usuario', compact('reemplazos', 'usuario'));
    }
}