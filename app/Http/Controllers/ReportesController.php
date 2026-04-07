<?php
// app/Http/Controllers/ReportesController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportesController extends Controller
{
    protected string $apiUrlProgramaciones = 'http://127.0.0.1:8001/programaciones/api/';

    /**
     * Verificar autenticación
     */
    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')->with('error', 'Por favor, inicie sesión para continuar.');
        }
        return null;
    }

    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function index()
    {
        // Verificar autenticación
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->apiUrlProgramaciones);

            $programaciones = collect([]);
            $total = 0;
            $confirmados = 0;
            $pendientes = 0;
            $reemplazados = 0;

            if ($response->successful()) {
                $data = $response->json();
                $programaciones = collect($data['data'] ?? $data ?? []);
                $total = $programaciones->count();
                $confirmados = $programaciones->where('estado', 'confirmado')->count();
                $pendientes = $programaciones->where('estado', 'pendiente')->count();
                $reemplazados = $programaciones->where('estado', 'reemplazado')->count();
            }

            return view('programacion.reportes', compact(
                'programaciones', 'total', 'confirmados', 'pendientes', 'reemplazados'
            ));

        } catch (\Exception $e) {
            Log::error('Error en reportes', ['error' => $e->getMessage()]);
            return view('programacion.reportes', [
                'total' => 0,
                'confirmados' => 0,
                'pendientes' => 0,
                'reemplazados' => 0,
                'programaciones' => collect([])
            ])->with('error', 'Error al cargar reportes: ' . $e->getMessage());
        }
    }

    /**
     * Exportar a Excel
     */
    public function exportarExcel(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $programaciones = $this->obtenerDatosConFiltros($request);
            
            // Crear contenido CSV (temporal mientras no tienes PhpSpreadsheet)
            $filename = 'reporte_programaciones_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://output', 'w');
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            fputcsv($handle, ['ID', 'Actividad', 'Asignación', 'Fecha', 'Estado']);
            
            foreach ($programaciones as $p) {
                fputcsv($handle, [
                    $p['id_programacion'] ?? $p['id'] ?? '',
                    $p['id_actividad'] ?? '',
                    $p['id_asignacion'] ?? '',
                    $p['fecha'] ?? '',
                    $p['estado'] ?? ''
                ]);
            }
            
            fclose($handle);
            
        } catch (\Exception $e) {
            Log::error('Error exportando Excel', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al exportar: ' . $e->getMessage());
        }
    }

    /**
     * Exportar a PDF (versión simple por ahora)
     */
    public function exportarPdf(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $programaciones = $this->obtenerDatosConFiltros($request);
            
            $total = count($programaciones);
            $confirmados = collect($programaciones)->where('estado', 'confirmado')->count();
            $pendientes = collect($programaciones)->where('estado', 'pendiente')->count();
            $reemplazados = collect($programaciones)->where('estado', 'reemplazado')->count();
            
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Reporte de Programaciones</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #1E3A8A; text-align: center; border-bottom: 2px solid #1E3A8A; padding-bottom: 10px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                    th { background: #1E3A8A; color: white; }
                    .summary { display: flex; justify-content: space-between; margin: 20px 0; }
                    .card { background: #f3f4f6; padding: 10px; border-radius: 5px; text-align: center; width: 23%; }
                    .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <h1>Reporte de Programaciones</h1>
                <div class="summary">
                    <div class="card"><strong>Total</strong><br>' . $total . '</div>
                    <div class="card"><strong>Confirmados</strong><br>' . $confirmados . '</div>
                    <div class="card"><strong>Pendientes</strong><br>' . $pendientes . '</div>
                    <div class="card"><strong>Reemplazados</strong><br>' . $reemplazados . '</div>
                </div>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Actividad</th><th>Asignación</th><th>Fecha</th><th>Estado</th></tr>
                    </thead>
                    <tbody>';
            
            foreach ($programaciones as $p) {
                $html .= '<tr>
                    <td>' . ($p['id_programacion'] ?? $p['id'] ?? '') . '</td>
                    <td>' . ($p['id_actividad'] ?? '') . '</td>
                    <td>' . ($p['id_asignacion'] ?? '') . '</td>
                    <td>' . ($p['fecha'] ?? '') . '</td>
                    <td>' . ($p['estado'] ?? '') . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>
                <div class="footer">Reporte generado el: ' . Carbon::now()->format('d/m/Y H:i:s') . '</div>
            </body></html>';
            
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="reporte_programaciones_' . Carbon::now()->format('Y-m-d_H-i-s') . '.html"');
            
        } catch (\Exception $e) {
            Log::error('Error exportando PDF', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al exportar: ' . $e->getMessage());
        }
    }

    /**
     * Exportar a CSV
     */
    public function exportarCsv(Request $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $programaciones = $this->obtenerDatosConFiltros($request);
            
            $filename = 'reporte_programaciones_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://output', 'w');
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            fputcsv($handle, ['ID', 'Actividad', 'Asignación', 'Fecha', 'Estado']);
            
            foreach ($programaciones as $p) {
                fputcsv($handle, [
                    $p['id_programacion'] ?? $p['id'] ?? '',
                    $p['id_actividad'] ?? '',
                    $p['id_asignacion'] ?? '',
                    $p['fecha'] ?? '',
                    $p['estado'] ?? ''
                ]);
            }
            
            fclose($handle);
            
        } catch (\Exception $e) {
            Log::error('Error exportando CSV', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al exportar: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos con filtros desde la API
     */
    private function obtenerDatosConFiltros(Request $request)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->get($this->apiUrlProgramaciones);

        $programaciones = collect([]);
        if ($response->successful()) {
            $data = $response->json();
            $programaciones = collect($data['data'] ?? $data ?? []);
        }

        // Aplicar filtros
        $fecha_desde = $request->get('fecha_desde');
        $fecha_hasta = $request->get('fecha_hasta');
        $estado = $request->get('estado');

        if ($fecha_desde) {
            $programaciones = $programaciones->filter(function ($p) use ($fecha_desde) {
                return ($p['fecha'] ?? '') >= $fecha_desde;
            });
        }

        if ($fecha_hasta) {
            $programaciones = $programaciones->filter(function ($p) use ($fecha_hasta) {
                return ($p['fecha'] ?? '') <= $fecha_hasta;
            });
        }

        if ($estado) {
            $programaciones = $programaciones->filter(function ($p) use ($estado) {
                return ($p['estado'] ?? '') == $estado;
            });
        }

        return $programaciones->values()->toArray();
    }
}