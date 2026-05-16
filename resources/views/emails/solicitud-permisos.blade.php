<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Permisos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e1e8ed;
        }
        .info-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .permiso-item {
            background: #e8f0fe;
            padding: 8px 12px;
            margin: 5px;
            border-radius: 5px;
            display: inline-block;
            font-size: 13px;
            font-weight: 500;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-pendiente {
            background: #ff9800;
            color: white;
        }
        hr {
            border: none;
            border-top: 1px solid #e1e8ed;
            margin: 20px 0;
        }
        .rol-card {
            background: #f0f0f0;
            border-left: 4px solid;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Nueva Solicitud de Permisos</h1>
            <p>Solicitud recibida el {{ $solicitud['fecha'] ?? now()->format('d/m/Y H:i:s') }}</p>
            <span class="badge badge-pendiente">Pendiente de Revisión</span>
        </div>

        <div class="content">
            <!-- Información del Solicitante -->
            <div class="info-box">
                <h3>👤 Información del Solicitante</h3>
                <p><strong>Nombre:</strong> {{ $usuario['nombre'] ?? 'No especificado' }}</p>
                <p><strong>Correo:</strong> {{ $usuario['correo'] ?? 'No especificado' }}</p>
                <p><strong>ID Usuario:</strong> {{ $usuario['id_usuario'] ?? 'No especificado' }}</p>
                @if(isset($solicitud['ip']))
                <p><strong>IP:</strong> {{ $solicitud['ip'] }}</p>
                @endif
            </div>

            <!-- Roles Solicitados -->
            <div class="info-box">
                <h3>🎭 Roles Solicitados ({{ $solicitud['total_roles'] ?? 0 }})</h3>
                @if(!empty($solicitud['roles_detalles']))
                    @foreach($solicitud['roles_detalles'] as $rol)
                        <div class="rol-card" style="border-left-color: {{ $rol['color'] }};">
                            <strong style="color: {{ $rol['color'] }};">{{ $rol['nombre'] }}</strong>
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                Permisos base: 
                                @foreach($rol['permisos_nombres'] as $permiso)
                                    <span style="background: #e8f0fe; padding: 2px 6px; border-radius: 4px; margin: 2px; display: inline-block; font-size: 11px;">{{ $permiso }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No se especificaron roles</p>
                @endif
            </div>

            <!-- Permisos Base Totales -->
            @if(!empty($solicitud['permisos_base_totales_nombres']))
            <div class="info-box">
                <h3>🔧 Permisos Base (combinados de roles)</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                    @foreach($solicitud['permisos_base_totales_nombres'] as $permiso)
                        <span class="permiso-item" style="background: #d4edda;">{{ $permiso }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Permisos Adicionales -->
            @if(!empty($solicitud['permisos_adicionales_nombres']))
            <div class="info-box">
                <h3>➕ Permisos Adicionales Solicitados</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                    @foreach($solicitud['permisos_adicionales_nombres'] as $permiso)
                        <span class="permiso-item" style="background: #fff3cd;">{{ $permiso }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Todos los Permisos Totales -->
            <div class="info-box">
                <h3>📋 Resumen Total de Permisos</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                    @foreach($solicitud['permisos_totales_nombres'] ?? [] as $permiso)
                        <span class="permiso-item">{{ $permiso }}</span>
                    @endforeach
                </div>
                <p style="margin-top: 10px;"><strong>Total:</strong> {{ $solicitud['total_modulos'] ?? 0 }} módulos</p>
            </div>

            <!-- Justificación -->
            <div class="info-box">
                <h3>📝 Justificación</h3>
                <p style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #e1e8ed;">
                    {{ $solicitud['justificacion'] ?? 'No se proporcionó justificación' }}
                </p>
            </div>

            <!-- Acciones Recomendadas -->
            <div class="info-box">
                <h3>⚡ Acciones Recomendadas</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Revisar la justificación del solicitante</li>
                    <li>Verificar el historial del usuario</li>
                    <li>Evaluar si los permisos son necesarios para su rol</li>
                    <li>Responder al usuario vía correo electrónico</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del <strong>Sistema de Gestión</strong></p>
            <p>Por favor, revisa esta solicitud en el panel de administración.</p>
            <hr>
            <p style="font-size: 11px;">
                &copy; {{ date('Y') }} Sistema de Gestión de Usuarios | 
                <a href="{{ url('/') }}" style="color: #667eea; text-decoration: none;">Ir al sistema</a>
            </p>
        </div>
    </div>
</body>
</html>