<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            margin: 20px;
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
            font-size: 18px;
        }
        .header p {
            color: #7F8C8D;
            margin: 5px 0;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #F8F9FA;
            padding: 8px;
            border-radius: 5px;
            width: 24%;
            text-align: center;
            border: 1px solid #E1E8ED;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 16px;
        }
        .stat-box p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #7F8C8D;
        }
        .ingreso { color: #27AE60; }
        .egreso { color: #E74C3C; }
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
            font-size: 10px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #BDC3C7;
            font-size: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #BDC3C7;
            font-size: 9px;
            color: #7F8C8D;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte Financiero</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3 class="ingreso">${{ number_format($totalIngresos, 0, ',', '.') }}</h3>
            <p>Total Ingresos</p>
        </div>
        <div class="stat-box">
            <h3 class="egreso">${{ number_format($totalEgresos, 0, ',', '.') }}</h3>
            <p>Total Egresos</p>
        </div>
        <div class="stat-box">
            <h3 style="color: {{ $balance >= 0 ? '#27AE60' : '#E74C3C' }}">${{ number_format($balance, 0, ',', '.') }}</h3>
            <p>Balance General</p>
        </div>
        <div class="stat-box">
            <h3>{{ $movimientos->count() }}</h3>
            <p>Movimientos</p>
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
        <tbody>
            @foreach($movimientos as $movimiento)
            <tr>
                <td>{{ $movimiento->id_movimiento }}</td>
                <td>{{ $movimiento->fecha }}</td>
                <td>{{ $movimiento->categoria->nombre_categoria ?? 'Sin categoría' }}</td>
                <td>{{ $movimiento->categoria->tipo_finanza ?? 'N/A' }}</td>
                <td class="{{ $movimiento->categoria->tipo_finanza == 'Ingreso' ? 'ingreso' : 'egreso' }}">
                    ${{ number_format($movimiento->monto, 0, ',', '.') }}
                </td>
                <td>{{ Str::limit($movimiento->descripcion ?? 'Sin descripción', 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión Financiera</p>
        <p>&copy; {{ date('Y') }} - Sistema JAKE</p>
    </div>
</body>
</html>