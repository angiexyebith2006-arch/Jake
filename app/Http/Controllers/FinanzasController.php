<?php

namespace App\Http\Controllers;

use App\Models\Finanzas;
use App\Models\CategoriaFinanza;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    public function index(Request $request)
    {
        $query = Finanzas::with('categoria');

        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

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

        $categorias = CategoriaFinanza::all();

        return view('finanzas.index', compact(
            'movimientos',
            'categorias',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'totalMovimientos'
        ));
    }

    public function create()
    {
        $categorias = CategoriaFinanza::all();
        return view('finanzas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:200',
        ]);

        Finanzas::create($validated);

        return redirect()->route('finanzas.index')
            ->with('success', 'Movimiento registrado correctamente');
    }

    public function show($id)
    {
        $movimiento = Finanzas::with('categoria')->find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        return view('finanzas.show', compact('movimiento'));
    }

    public function edit($id)
    {
        $movimiento = Finanzas::find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        $categorias = CategoriaFinanza::all();

        return view('finanzas.edit', compact('movimiento', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $movimiento = Finanzas::find($id);

        if (!$movimiento) {
            return redirect()->route('finanzas.index')
                ->with('error', 'Movimiento no encontrado');
        }

        $validated = $request->validate([
            'id_categoria' => 'required|exists:categorias_finanzas,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $movimiento->update($validated);

        return redirect()->route('finanzas.index')
            ->with('success', 'Movimiento actualizado correctamente');
    }

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

   public function reportes(Request $request)
    {
        $query = Finanzas::with('categoria');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get();

        $totalIngresos = $movimientos->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        return view('finanzas.reportes', compact(
            'movimientos',
            'totalIngresos',
            'totalEgresos',
            'balance'
        ));
    }

    

    /**
     * Generar reporte en CSV (compatible con Excel)
     */
    public function reporteCsv(Request $request)
    {
        $query = Finanzas::with('categoria');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get();

        $totalIngresos = $movimientos->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        $filename = 'reporte_financiero_' . date('Y-m-d_His') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // UTF-8 BOM para caracteres especiales
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($handle, ['ID', 'Fecha', 'Categoría', 'Tipo', 'Monto', 'Descripción', 'Fecha de Reporte']);
        
        // Datos
        foreach ($movimientos as $movimiento) {
            fputcsv($handle, [
                $movimiento->id_movimiento,
                $movimiento->fecha,
                $movimiento->categoria->nombre_categoria ?? 'Sin categoría',
                $movimiento->categoria->tipo_finanza ?? 'N/A',
                '$' . number_format($movimiento->monto, 0, ',', '.'),
                $movimiento->descripcion ?? 'Sin descripción',
                now()->format('d/m/Y H:i:s')
            ]);
        }
        
        // Agregar resumen
        fputcsv($handle, []);
        fputcsv($handle, ['RESUMEN GENERAL', '', '', '', '', '', '']);
        fputcsv($handle, ['Total Ingresos:', '$' . number_format($totalIngresos, 0, ',', '.'), '', '', '', '', '']);
        fputcsv($handle, ['Total Egresos:', '$' . number_format($totalEgresos, 0, ',', '.'), '', '', '', '', '']);
        fputcsv($handle, ['Balance General:', '$' . number_format($balance, 0, ',', '.'), '', '', '', '', '']);
        fputcsv($handle, ['Total Movimientos:', $movimientos->count(), '', '', '', '', '']);
        fputcsv($handle, ['Fecha de generación:', now()->format('d/m/Y H:i:s'), '', '', '', '', '']);
        
        fclose($handle);
        exit;
    }

    /**
     * Generar reporte en PDF usando HTML (el usuario puede usar "Imprimir > Guardar como PDF")
     */
    public function reportePdf(Request $request)
    {
        $query = Finanzas::with('categoria');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get();

        $totalIngresos = $movimientos->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        $html = $this->generarHtmlReporte($movimientos, $totalIngresos, $totalEgresos, $balance);
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="reporte_financiero_' . date('Y-m-d_His') . '.html"');
    }

    /**
     * Alias para Excel (usa el mismo CSV)
     */
    public function reporteExcel(Request $request)
    {
        return $this->reporteCsv($request);
    }

    /**
     * Generar HTML para el reporte financiero
     */
    private function generarHtmlReporte($movimientos, $totalIngresos, $totalEgresos, $balance)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte Financiero</title>
            <style>
                @media print {
                    body { margin: 0; padding: 20px; }
                    .no-print { display: none; }
                    table { page-break-inside: avoid; }
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    margin: 0;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #2C3E50;
                    padding-bottom: 10px;
                }
                .header h1 {
                    color: #2C3E50;
                    margin: 0;
                    font-size: 20px;
                }
                .header p {
                    color: #7F8C8D;
                    margin: 5px 0;
                }
                .stats {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                    gap: 10px;
                }
                .stat-box {
                    background: #F8F9FA;
                    padding: 10px;
                    border-radius: 5px;
                    width: 25%;
                    text-align: center;
                    border: 1px solid #E1E8ED;
                }
                .stat-box h3 {
                    margin: 0;
                    font-size: 20px;
                }
                .stat-box p {
                    margin: 5px 0 0;
                    color: #7F8C8D;
                }
                .ingreso { color: #27AE60; }
                .egreso { color: #E74C3C; }
                .balance-positivo { color: #27AE60; }
                .balance-negativo { color: #E74C3C; }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                th {
                    background: #2C3E50;
                    color: white;
                    padding: 8px;
                    text-align: left;
                }
                td {
                    padding: 8px;
                    border-bottom: 1px solid #BDC3C7;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 1px solid #BDC3C7;
                    font-size: 10px;
                    color: #7F8C8D;
                }
                button {
                    background: #3498DB;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    margin-bottom: 20px;
                }
                button:hover {
                    background: #2980B9;
                }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print();">🖨️ Imprimir / Guardar como PDF</button>
                <p style="color: #7F8C8D; margin-top: 5px;">Presione el botón y luego seleccione "Guardar como PDF"</p>
            </div>
            
            <div class="header">
                <h1>💰 Reporte Financiero del Sistema</h1>
                <p>Fecha de generación: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        
            <div class="stats">
                <div class="stat-box">
                    <h3 class="ingreso">$' . number_format($totalIngresos, 0, ',', '.') . '</h3>
                    <p>📈 Total Ingresos</p>
                </div>
                <div class="stat-box">
                    <h3 class="egreso">$' . number_format($totalEgresos, 0, ',', '.') . '</h3>
                    <p>📉 Total Egresos</p>
                </div>
                <div class="stat-box">
                    <h3 class="' . ($balance >= 0 ? 'balance-positivo' : 'balance-negativo') . '">$' . number_format($balance, 0, ',', '.') . '</h3>
                    <p>⚖️ Balance General</p>
                </div>
                <div class="stat-box">
                    <h3>' . $movimientos->count() . '</h3>
                    <p>🔄 Total Movimientos</p>
                </div>
            </div>
        
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($movimientos as $movimiento) {
            $tipo = $movimiento->categoria->tipo_finanza ?? 'N/A';
            $claseMonto = ($tipo == 'Ingreso') ? 'ingreso' : 'egreso';
            
            $html .= '
                    <tr>
                        <td>' . $movimiento->id_movimiento . '</td>
                        <td>' . $movimiento->fecha . '</td>
                        <td>' . htmlspecialchars($movimiento->categoria->nombre_categoria ?? 'Sin categoría') . '</td>
                        <td>' . $tipo . '</td>
                        <td class="' . $claseMonto . '">$' . number_format($movimiento->monto, 0, ',', '.') . '</td>
                        <td>' . htmlspecialchars($movimiento->descripcion ?? 'Sin descripción') . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        
            <div class="footer">
                <p>Reporte generado automáticamente por el Sistema de Gestión Financiera</p>
                <p>© ' . date('Y') . ' - Todos los derechos reservados</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    

    /**
 * Generar reporte (método principal)
 */
public function reporte(Request $request)
{
    $query = Finanzas::with('categoria');

    if ($request->filled('fecha_inicio')) {
        $query->where('fecha', '>=', $request->fecha_inicio);
    }

    if ($request->filled('fecha_fin')) {
        $query->where('fecha', '<=', $request->fecha_fin);
    }

    $movimientos = $query->orderBy('fecha', 'desc')->get();

    $totalIngresos = $movimientos->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
    $totalEgresos = $movimientos->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
    $balance = $totalIngresos - $totalEgresos;

    return view('finanzas.reportes', compact(
        'movimientos',
        'totalIngresos',
        'totalEgresos',
        'balance'
    ));
}

    public function dashboard()
    {
        $mesActual = now()->format('Y-m');

        $movimientosMes = Finanzas::where('fecha', 'like', $mesActual . '%')
            ->with('categoria')
            ->get();

        $ingresosMes = $movimientosMes->where('categoria.tipo_finanza', 'Ingreso')->sum('monto');
        $egresosMes = $movimientosMes->where('categoria.tipo_finanza', 'Egreso')->sum('monto');
        $balanceMes = $ingresosMes - $egresosMes;

        $ultimosMovimientos = Finanzas::with('categoria')
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