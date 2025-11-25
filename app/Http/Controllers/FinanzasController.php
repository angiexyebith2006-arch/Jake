<?php

namespace App\Http\Controllers;

use App\Models\Finanza;
use App\Models\Ministerio;
use App\Models\CategoriasFinanzas;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanzasController extends Controller
{
    /**
     * Mostrar lista de movimientos financieros.
     */
    public function index(Request $request)
    {
        $query = Finanza::with(['ministerio', 'categoria', 'registradoPor']);

        // Filtros
        if ($request->has('id_ministerio') && $request->id_ministerio != '') {
            $query->where('id_ministerio', $request->id_ministerio);
        }

        if ($request->has('id_categoria') && $request->id_categoria != '') {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'desc')
                           ->orderBy('id_movimiento', 'desc')
                           ->get();

        $ministerios = Ministerio::all();
        $categorias = CategoriaFinanza::all();

        return view('finanzas.index', compact('movimientos', 'ministerios', 'categorias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $ministerios = Ministerio::all();
        $categorias = CategoriaFinanza::all();
        $usuarios = Usuario::where('activo', true)->get();
        
        return view('finanzas.create', compact('ministerios', 'categorias', 'usuarios'));
    }

    /**
     * Guardar nuevo movimiento financiero.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
                'monto' => 'required|numeric|min:0.01',
                'fecha' => 'required|date',
                'descripcion' => 'required|string|max:200',
                'registrado_por' => 'sometimes|exists:usuarios,id_usuario'
            ]);

            // Si no se especifica quien registra, usar el usuario autenticado
            if (!isset($validated['registrado_por']) && Auth::check()) {
                $validated['registrado_por'] = Auth::id();
            }

            Finanza::create($validated);

            return redirect()->route('finanzas.index')
                ->with('success', 'Movimiento financiero registrado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar el movimiento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar movimiento específico.
     */
    public function show($id)
    {
        $movimiento = Finanza::with(['ministerio', 'categoria', 'registradoPor'])->find($id);
        
        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento financiero no encontrado.');
        }
        
        return view('finanzas.show', compact('movimiento'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $movimiento = Finanza::find($id);
        $ministerios = Ministerio::all();
        $categorias = CategoriaFinanza::all();
        $usuarios = Usuario::where('activo', true)->get();
       
        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento financiero no encontrado.');
        }
        
        return view('finanzas.edit', compact('movimiento', 'ministerios', 'categorias', 'usuarios'));
    }

    /**
     * Actualizar movimiento financiero.
     */
    public function update(Request $request, $id)
    {
        $movimiento = Finanza::find($id);
        
        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento financiero no encontrado.');
        }

        try {
            $validated = $request->validate([
                'id_ministerio' => 'required|exists:ministerios,id_ministerio',
                'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
                'monto' => 'required|numeric|min:0.01',
                'fecha' => 'required|date',
                'descripcion' => 'required|string|max:200',
                'registrado_por' => 'sometimes|exists:usuarios,id_usuario'
            ]);

            $movimiento->update($validated);

            return redirect()->route('finanzas.index')
                ->with('success', 'Movimiento financiero actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el movimiento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar movimiento financiero.
     */
    public function destroy($id)
    {
        try {
            $movimiento = Finanza::findOrFail($id);
            $movimiento->delete();

            return redirect()->route('finanzas.index')
                ->with('success', 'Movimiento financiero eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Error al eliminar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar reporte de finanzas.
     */
    public function reporte(Request $request)
    {
        $query = Finanza::with(['ministerio', 'categoria']);

        // Filtros para el reporte
        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha')->get();

        // Cálculos para el reporte
        $totalIngresos = $movimientos->where('categoria.tipo', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('categoria.tipo', 'Egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        // Agrupar por ministerio
        $porMinisterio = $movimientos->groupBy('id_ministerio')->map(function ($movimientosMinisterio) {
            $ministerio = $movimientosMinisterio->first()->ministerio;
            $ingresos = $movimientosMinisterio->where('categoria.tipo', 'Ingreso')->sum('monto');
            $egresos = $movimientosMinisterio->where('categoria.tipo', 'Egreso')->sum('monto');
            
            return [
                'ministerio' => $ministerio,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'balance' => $ingresos - $egresos
            ];
        });

        $ministerios = Ministerio::all();

        return view('finanzas.reporte', compact(
            'movimientos', 
            'totalIngresos', 
            'totalEgresos', 
            'balance', 
            'porMinisterio',
            'ministerios'
        ));
    }

    /**
     * Mostrar dashboard de finanzas.
     */
    public function dashboard()
    {
        // Movimientos del mes actual
        $mesActual = now()->format('Y-m');
        $movimientosMes = Finanza::where('fecha', 'like', $mesActual . '%')
            ->with(['categoria'])
            ->get();

        $ingresosMes = $movimientosMes->where('categoria.tipo', 'Ingreso')->sum('monto');
        $egresosMes = $movimientosMes->where('categoria.tipo', 'Egreso')->sum('monto');
        $balanceMes = $ingresosMes - $egresosMes;

        // Últimos 5 movimientos
        $ultimosMovimientos = Finanza::with(['ministerio', 'categoria'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id_movimiento', 'desc')
            ->take(5)
            ->get();

        // Totales por ministerio
        $totalesMinisterios = Ministerio::with(['finanzas.categoria'])->get()->map(function ($ministerio) {
            $ingresos = $ministerio->finanzas->where('categoria.tipo', 'Ingreso')->sum('monto');
            $egresos = $ministerio->finanzas->where('categoria.tipo', 'Egreso')->sum('monto');
            
            return [
                'ministerio' => $ministerio,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'balance' => $ingresos - $egresos
            ];
        });

        return view('finanzas.dashboard', compact(
            'ingresosMes',
            'egresosMes',
            'balanceMes',
            'ultimosMovimientos',
            'totalesMinisterios'
        ));
    }
}