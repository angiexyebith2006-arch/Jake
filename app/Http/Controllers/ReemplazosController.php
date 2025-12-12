<?php

namespace App\Http\Controllers;

use App\Models\Reemplazo;
use App\Models\Programacion;
use App\Models\Asignacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReemplazosController extends Controller
{
    /**
     * Mostrar lista de reemplazos.
     */
    public function index(Request $request)
    {
        try {
            $query = Reemplazo::with([
                'programacion.actividad.ministerio',
                'asignacionReemplazado.usuario',
                'asignacionReemplazoPor.usuario',
                'autorizaciones.autorizador'
            ]);

            // Filtros
            if ($request->has('estado') && $request->estado != '') {
                $query->where('estado', $request->estado);
            }

            if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
                $query->where('fecha_solicitud', '>=', $request->fecha_inicio);
            }

            if ($request->has('fecha_fin') && $request->fecha_fin != '') {
                $query->where('fecha_solicitud', '<=', $request->fecha_fin);
            }

            $reemplazos = $query->orderBy('fecha_solicitud', 'desc')
                               ->orderBy('id_reemplazo', 'desc')
                               ->get();

            return view('reemplazos.index', compact('reemplazos'));

        } catch (\Exception $e) {
            Log::error('Error en ReemplazosController@index: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar los reemplazos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(Request $request)
    {
        try {
            $idProgramacion = $request->get('programacion_id');
            
            $programaciones = Programacion::with(['actividad.ministerio', 'asignacion.usuario'])
                ->where('estado', 'Pendiente')
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

        } catch (\Exception $e) {
            Log::error('Error en ReemplazosController@create: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nueva solicitud de reemplazo.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'id_programacion' => 'required|exists:programaciones,id_programacion',
                'id_asignacion_reemplazado' => 'required|exists:asignaciones,id_asignacion',
                'id_asignacion_reemplazo_por' => 'required|exists:asignaciones,id_asignacion',
                'motivo' => 'required|string|max:200'
            ]);

            // Verificar que la programación existe
            $programacion = Programacion::with('asignacion')->findOrFail($validated['id_programacion']);
            
            // Verificar que el estado sea Pendiente
            if ($programacion->estado !== 'Pendiente') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'No se puede solicitar reemplazo para una programación confirmada o reemplazada.')
                    ->withInput();
            }

            // Verificar que el reemplazado es quien está programado
            if ($programacion->id_asignacion != $validated['id_asignacion_reemplazado']) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'El servidor a reemplazar no coincide con la programación.')
                    ->withInput();
            }

            // Verificar que no existe un reemplazo pendiente para esta programación
            $reemplazoExistente = Reemplazo::where('id_programacion', $validated['id_programacion'])
                ->where('estado', 'Pendiente')
                ->exists();

            if ($reemplazoExistente) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Ya existe una solicitud de reemplazo pendiente para esta programación.')
                    ->withInput();
            }

            // Verificar que el reemplazo es del mismo ministerio
            $reemplazado = Asignacion::find($validated['id_asignacion_reemplazado']);
            $reemplazoPor = Asignacion::find($validated['id_asignacion_reemplazo_por']);

            if ($reemplazado->id_ministerio != $reemplazoPor->id_ministerio) {
                DB::rollBack();
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
                'fecha_solicitud' => Carbon::now()->format('Y-m-d'),
                'estado' => 'Pendiente'
            ]);

            // Cambiar estado de la programación a "Reemplazado"
            $programacion->update(['estado' => 'Reemplazado']);

            // Crear autorización automática
            $idAutorizador = $this->obtenerAutorizadorPorDefecto();
            
            DB::table('autorizaciones')->insert([
                'id_programacion' => $validated['id_programacion'],
                'id_reemplazo' => $reemplazo->id_reemplazo,
                'id_usuario_solicitante' => $reemplazado->id_usuario,
                'id_usuario_reemplazo' => $reemplazoPor->id_usuario,
                'id_autorizador' => $idAutorizador,
                'motivo' => $validated['motivo'],
                'tipo' => 'reemplazo',
                'estado' => 'pendiente',
                'fecha_solicitud' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            DB::commit();

            return redirect()->route('reemplazos.index')
                ->with('success', 'Solicitud de reemplazo creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ReemplazosController@store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear la solicitud de reemplazo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtener autorizador por defecto
     */
    private function obtenerAutorizadorPorDefecto()
    {
        try {
            $autorizador = DB::table('usuarios')
                ->join('asignaciones', 'usuarios.id_usuario', '=', 'asignaciones.id_usuario')
                ->join('roles', 'asignaciones.id_rol', '=', 'roles.id_rol')
                ->where(function($query) {
                    $query->where('roles.nombre_rol', 'like', '%admin%')
                          ->orWhere('roles.nombre_rol', 'like', '%Admin%')
                          ->orWhere('roles.nombre_rol', 'like', '%lider%')
                          ->orWhere('roles.nombre_rol', 'like', '%Líder%')
                          ->orWhere('roles.nombre_rol', 'like', '%coordinador%')
                          ->orWhere('roles.nombre_rol', 'like', '%Coordinador%');
                })
                ->where('usuarios.activo', true)
                ->where('asignaciones.activo', true)
                ->orderBy('usuarios.id_usuario', 'asc')
                ->select('usuarios.id_usuario')
                ->first();

            if ($autorizador) {
                return $autorizador->id_usuario;
            }

            return 1;

        } catch (\Exception $e) {
            Log::error('Error en obtenerAutorizadorPorDefecto: ' . $e->getMessage());
            return 1;
        }
    }
}