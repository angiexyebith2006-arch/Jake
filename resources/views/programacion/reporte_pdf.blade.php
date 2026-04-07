<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Programaciones</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }
        h1 {
            color: #1E3A8A;
            text-align: center;
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 10px;
        }
        .filters {
            background: #f3f4f6;
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .card {
            background: #f9fafb;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            width: 23%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #1E3A8A;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Reporte de Programaciones</h1>
    
    <div class="filters">
        <strong>Filtros aplicados:</strong>
        @if($filtros['fecha_desde']) Fecha desde: {{ $filtros['fecha_desde'] }} @endif
        @if($filtros['fecha_hasta']) Fecha hasta: {{ $filtros['fecha_hasta'] }} @endif
        @if($filtros['estado']) Estado: {{ $filtros['estado'] }} @endif
        @if(!$filtros['fecha_desde'] && !$filtros['fecha_hasta'] && !$filtros['estado']) Todos los registros @endif
    </div>
    
    <div class="summary">
        <div class="card">
            <strong>Total</strong><br>
            <span style="font-size: 20px;">{{ $total }}</span>
        </div>
        <div class="card">
            <strong>Confirmados</strong><br>
            <span style="font-size: 20px; color: #10B981;">{{ $confirmados }}</span>
        </div>
        <div class="card">
            <strong>Pendientes</strong><br>
            <span style="font-size: 20px; color: #F59E0B;">{{ $pendientes }}</span>
        </div>
        <div class="card">
            <strong>Reemplazados</strong><br>
            <span style="font-size: 20px; color: #6B7280;">{{ $reemplazados }}</span>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Actividad</th>
                <th>Asignación</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programaciones as $p)
            <tr>
                <td>{{ $p['id_programacion'] ?? $p['id'] ?? '' }}</td>
                <td>{{ $p['id_actividad'] ?? '' }}</td>
                <td>{{ $p['id_asignacion'] ?? '' }}</td>
                <td>{{ $p['fecha'] ?? '' }}</td>
                <td>{{ ucfirst($p['estado'] ?? '') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay programaciones registradas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Reporte generado el: {{ $fecha_reporte }}
    </div>
</body>
</html>