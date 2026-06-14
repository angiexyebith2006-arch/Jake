<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    protected string $apiUrl = 'http://127.0.0.1:5431/api/permisos';

    public function index()
    {
        try {
            $permisos = Http::get($this->apiUrl)->json() ?? [];
        } catch (\Exception $e) {
            $permisos = [];
        }

        try {
            $asignaciones = Http::get('http://127.0.0.1:5431/api/asignaciones')->json() ?? [];
            $nombresAsignaciones = collect($asignaciones)
                ->keyBy('idAsignacion')
                ->map(fn($a) => $a['usuarioNombre'] ?? 'Sin nombre')
                ->toArray();
        } catch (\Exception $e) {
            $nombresAsignaciones = [];
        }

        $agrupados = [];
        foreach ($permisos as $p) {
            $idAsignacion = $p['idAsignacion'];
            $vistaNombre  = $p['vista'];

            if (!isset($agrupados[$idAsignacion])) {
                $agrupados[$idAsignacion] = [
                    'idAsignacion' => $idAsignacion,
                    'nombre'       => $nombresAsignaciones[$idAsignacion] ?? 'Sin nombre',
                    'vistas'       => []
                ];
            }

            if (!isset($agrupados[$idAsignacion]['vistas'][$vistaNombre])) {
                $agrupados[$idAsignacion]['vistas'][$vistaNombre] = [
                    'id'       => $p['id'],
                    'acciones' => []
                ];
            }

            foreach ($p['acciones'] as $accion) {
                $agrupados[$idAsignacion]['vistas'][$vistaNombre]['acciones'][] = $accion;
            }
        }

        foreach ($agrupados as &$grupo) {
            foreach ($grupo['vistas'] as &$vista) {
                $vista['acciones'] = array_values(array_unique($vista['acciones']));
            }
        }

        return view('perfil.permiso.index', compact('agrupados'));
    }

    public function create()
    {
        try {
            $vistasResponse = Http::get('http://127.0.0.1:5431/api/vistas')->json();
            $vistas = $vistasResponse['data'] ?? $vistasResponse ?? [];
        } catch (\Exception $e) {
            $vistas = [];
        }

        try {
            $asignacionesResponse = Http::get('http://127.0.0.1:5431/api/asignaciones')->json();
            $asignaciones = $asignacionesResponse['data'] ?? $asignacionesResponse ?? [];
        } catch (\Exception $e) {
            $asignaciones = [];
        }

        $acciones = [
            ['id' => 1, 'nombre' => 'crear'],
            ['id' => 2, 'nombre' => 'editar'],
            ['id' => 3, 'nombre' => 'eliminar'],
            ['id' => 4, 'nombre' => 'ver'],
            ['id' => 5, 'nombre' => 'responder'],
        ];

        return view('perfil.permiso.create', compact('vistas', 'acciones', 'asignaciones'));
    }

    public function storeMultiple(Request $request)
    {
        $permisos     = $request->input('permisos', []);
        $idAsignacion = $request->input('asignacion_id');

        if (empty($permisos) || empty($idAsignacion)) {
            return redirect()->route('permisos.index')
                ->with('error', 'Debes seleccionar una asignación y al menos un permiso');
        }

        try {
            $existentes = Http::get($this->apiUrl)->json() ?? [];
        } catch (\Exception $e) {
            $existentes = [];
        }

        $agrupados = [];
        foreach ($permisos as $permiso) {
            [$vistaNombre, $nombreAccion] = explode('-', $permiso);
            $agrupados[$vistaNombre][] = $nombreAccion;
        }

        $duplicados = false;

        foreach ($agrupados as $vistaNombre => $acciones) {
            $yaExiste = collect($existentes)->contains(function ($p) use ($idAsignacion, $vistaNombre) {
                return $p['idAsignacion'] == $idAsignacion && $p['vista'] == $vistaNombre;
            });

            if ($yaExiste) {
                $duplicados = true;
                continue;
            }

            Http::post($this->apiUrl, [
                'idAsignacion' => (int) $idAsignacion,
                'vista'        => $vistaNombre,
                'acciones'     => array_values(array_unique($acciones)),
            ]);
        }

        if ($duplicados) {
            return redirect()->route('permisos.index')
                ->with('error', 'Algunos permisos ya existen y no fueron guardados');
        }

        return redirect()->route('permisos.index')
            ->with('success', 'Permisos guardados correctamente');
    }

    public function edit($id)
    {
        try {
            $permiso = Http::get($this->apiUrl . '/' . $id)->json() ?? [];
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')->with('error', 'Permiso no encontrado');
        }

        $idAsignacion = $permiso['idAsignacion'];

        try {
            $todosPermisos = Http::get($this->apiUrl)->json() ?? [];
        } catch (\Exception $e) {
            $todosPermisos = [];
        }

        $permisosAsignacion = collect($todosPermisos)
            ->where('idAsignacion', $idAsignacion)
            ->keyBy('vista')
            ->toArray();

        $permisos = [];
        foreach ($permisosAsignacion as $vistaNombre => $datos) {
            $permisos[$vistaNombre] = array_values(array_unique($datos['acciones'] ?? []));
        }

        try {
            $vistasResponse = Http::get('http://127.0.0.1:5431/api/vistas')->json();
            $vistas = $vistasResponse['data'] ?? $vistasResponse ?? [];
        } catch (\Exception $e) {
            $vistas = [];
        }

        try {
            $asignacionesResponse = Http::get('http://127.0.0.1:5431/api/asignaciones')->json();
            $asignaciones = $asignacionesResponse['data'] ?? $asignacionesResponse ?? [];
        } catch (\Exception $e) {
            $asignaciones = [];
        }

        $acciones = [
            ['id' => 1, 'nombre' => 'crear'],
            ['id' => 2, 'nombre' => 'editar'],
            ['id' => 3, 'nombre' => 'eliminar'],
            ['id' => 4, 'nombre' => 'ver'],
            ['id' => 5, 'nombre' => 'responder'],
        ];

        return view('perfil.permiso.edit', compact(
            'permiso',
            'permisosAsignacion',
            'idAsignacion',
            'vistas',
            'acciones',
            'asignaciones',
            'permisos'
        ));
    }

    public function update(Request $request, $id)
    {
        $idAsignacion = (int) $request->input('asignacion_id');
        $permisos     = $request->input('permisos', []);

        $enviados = [];
        foreach ($permisos as $vista => $acciones) {
            $enviados[$vista] = array_values(array_unique($acciones));
        }

        try {
            $todosPermisos = Http::get($this->apiUrl)->json() ?? [];
        } catch (\Exception $e) {
            $todosPermisos = [];
        }

        $actuales = collect($todosPermisos)
            ->where('idAsignacion', $idAsignacion)
            ->values();

        foreach ($actuales as $permisoActual) {
            Http::delete($this->apiUrl . '/' . $permisoActual['id']);
        }

        foreach ($enviados as $vista => $acciones) {
            Http::post($this->apiUrl, [
                'idAsignacion' => $idAsignacion,
                'vista'        => $vista,
                'acciones'     => $acciones,
            ]);
        }

        return redirect()->route('permisos.index')
            ->with('success', 'Permisos actualizados correctamente');
    }

    /**
     * Elimina el permiso completo por su ID.
     * Como Java maneja las acciones dentro de un mismo registro (idAsignacion + vista),
     * basta con eliminar ese único registro por su ID.
     */
    public function destroy($id)
    {
        try {
            // ✅ Solo eliminamos el registro por su ID directamente
            // No hace falta buscar relacionados — Java ya agrupa todas las acciones en un solo registro
            $response = Http::delete($this->apiUrl . '/' . $id);

            if ($response->successful()) {
                return redirect()->route('permisos.index')
                    ->with('success', 'Permiso eliminado correctamente.');
            }

            return redirect()->route('permisos.index')
                ->with('error', 'No se pudo eliminar el permiso. Código: ' . $response->status());

        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}