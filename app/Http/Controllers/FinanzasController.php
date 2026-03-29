<?php

namespace App\Http\Controllers;

use App\Models\Finanzas;
use App\Models\Ministerio;
use App\Models\CategoriasFinanza;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    /**
     * Listar movimientos
     */
    public function index(Request $request)
    {
        $query = Finanzas::with(['ministerio', 'categoria']);

        // Filtros
        if ($request->filled('id_ministerio')) {
            $query->where('id_ministerio', $request->id_ministerio);
        }

        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        // Totales
        $totalIngresos = Finanzas::join('categorias_finanzas', 'finanzas.id_categoria', '=', 'categorias_finanzas.id_categoria')
            ->where('categorias_finanzas.tipo_finanza', 'Ingreso')
            ->sum('finanzas.monto');

        $totalEgresos = Finanzas::join('categorias_finanzas', 'finanzas.id_categoria', '=', 'categorias_finanzas.id_categoria')
            ->where('categorias_finanzas.tipo_finanza', 'Egreso')
            ->sum('finanzas.monto');

        $balance = $totalIngresos - $totalEgresos;
        $totalMovimientos = $query->count();

        $movimientos = $query->orderBy('fecha', 'desc')
            ->orderBy('id_movimiento', 'desc')
            ->get();

        $ministerios = Ministerio::all();
        $categorias = CategoriasFinanza::all();

        return view('finanzas.index', compact(
            'movimientos',
            'ministerios',
            'categorias',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'totalMovimientos'
        ));
    }

    /**
     * Formulario crear
     */
    public function create()
    {
        $ministerios = Ministerio::all();
        $categorias = CategoriasFinanza::all();

        return view('finanzas.create', compact('ministerios', 'categorias'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ministerio' => 'required|exists:ministerios,id_ministerio',
            'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:200',
        ]);

        Finanzas::create($validated);

        return redirect()->route('finanzas.index')
            ->with('success', 'Movimiento registrado correctamente');
    }

    /**
     * Mostrar uno
     */
    public function show($id)
    {
        $movimiento = Finanzas::with(['ministerio', 'categoria'])->find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        return view('finanzas.show', compact('movimiento'));
    }

    /**
     * Formulario editar
     */
    public function edit($id)
    {
        $movimiento = Finanzas::find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        $ministerios = Ministerio::all();
        $categorias = CategoriasFinanza::all();

        return view('finanzas.edit', compact('movimiento', 'ministerios', 'categorias'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, $id)
    {
        $movimiento = Finanzas::find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        $validated = $request->validate([
            'id_ministerio' => 'required|exists:ministerios,id_ministerio',
            'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $movimiento->update($validated);

        return redirect()->route('finanzas.index')
            ->with('success', 'Movimiento actualizado correctamente');
    }

    /**
     * Eliminar
     */
    public function destroy($id)
    {
        $movimiento = Finanzas::find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        $movimiento->delete();

        return redirect()->route('finanzas.index')
            ->with('success', 'Movimiento eliminado correctamente');
    }

    /**
     * Reporte
     */
    public function reporte(Request $request)
    {
        $query = Finanzas::with(['ministerio', 'categoria']);

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha')->get();

        $totalIngresos = $movimientos->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        $porMinisterio = $movimientos->groupBy('id_ministerio')->map(function ($items) {
            $ministerio = $items->first()->ministerio;

            $ingresos = $items->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
            $egresos = $items->where('categoria.tipo_finanza', 'Egreso')->sum('monto');

            return [
                'ministerio' => $ministerio,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'balance' => $ingresos - $egresos
            ];
        });

        return view('finanzas.reporte', compact(
            'movimientos',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'porMinisterio'
        ));
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $mesActual = now()->format('Y-m');

        $movimientosMes = Finanzas::where('fecha', 'like', $mesActual . '%')
            ->with(['categoria'])
            ->get();

        $ingresosMes = $movimientosMes->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $egresosMes = $movimientosMes->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balanceMes = $ingresosMes - $egresosMes;

        $ultimosMovimientos = Finanzas::with(['ministerio', 'categoria'])
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        return view('finanzas.dashboard', compact(
            'ingresosMes',
            'egresosMes',
            'balanceMes',
            'ultimosMovimientos'
        ));
    }
}