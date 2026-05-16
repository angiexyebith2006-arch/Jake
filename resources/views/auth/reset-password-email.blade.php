<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #facc15, #f59e0b); padding: 32px; text-align: center; }
        .header h1 { color: #1f2937; margin: 0; font-size: 22px; }
        .body { padding: 32px; }
        .body p { color: #4b5563; font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .btn { display: block; width: fit-content; margin: 24px auto; background: #facc15; color: #1f2937; font-weight: bold; font-size: 15px; padding: 14px 32px; border-radius: 10px; text-decoration: none; }
        .note { background: #fef9c3; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>🔐 Recuperar contraseña</h1>
    </div>

    <div class="body">
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>JAKE</strong>.</p>
        <p>Haz clic en el botón para crear una nueva contraseña:</p>

        <a href="{{ $resetLink }}" class="btn">Restablecer contraseña</a>

        <div class="note">
            ⚠️ Este enlace expira en <strong>30 minutos</strong>. Si no solicitaste este cambio, ignora este correo — tu contraseña no cambiará.
        </div>
    </div>

    <div class="footer">
        © {{ date('Y') }} JAKE · Este es un correo automático, no respondas a este mensaje.
    </div>

</div>
</body>
</html>