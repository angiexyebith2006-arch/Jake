<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2C3E50;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2C3E50;
            margin: 0;
        }
        .header p {
            color: #7F8C8D;
            margin: 5px 0;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            text-align: center;
        }
        .stat-box {
            background: #F8F9FA;
            padding: 10px;
            border-radius: 8px;
            width: 30%;
        }
        .stat-box h3 {
            margin: 0;
            color: #3498DB;
            font-size: 20px;
        }
        .stat-box p {
            margin: 5px 0 0;
            color: #7F8C8D;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #2C3E50;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #BDC3C7;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #BDC3C7;
            font-size: 10px;
            color: #7F8C8D;
        }
        .activo {
            color: #27AE60;
            font-weight: bold;
        }
        .inactivo {
            color: #E74C3C;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Usuarios del Sistema</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>{{ $total }}</h3>
            <p>Total Usuarios</p>
        </div>
        <div class="stat-box">
            <h3>{{ $activos }}</h3>
            <p>Usuarios Activos</p>
        </div>
        <div class="stat-box">
            <h3>{{ $inactivos }}</h3>
            <p>Usuarios Inactivos</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario['idUsuario'] ?? $usuario['id_usuario'] ?? '' }}</td>
                <td>{{ $usuario['nombre'] ?? '' }}</td>
                <td>{{ $usuario['correo'] ?? '' }}</td>
                <td>{{ $usuario['telefono'] ?? 'N/A' }}</td>
                <td class="{{ ($usuario['activo'] ?? false) ? 'activo' : 'inactivo' }}">
                    {{ ($usuario['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión de Usuarios</p>
        <p>Este documento contiene información confidencial del sistema</p>
    </div>
</body>
</html>