<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudPermisosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $usuario;

    public function __construct($solicitud, $usuario)
    {
        $this->solicitud = $solicitud;
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'sistema@example.com'), env('MAIL_FROM_NAME', 'Sistema'))
                    ->subject('📋 Nueva Solicitud de Permisos - ' . ($this->usuario['nombre'] ?? 'Usuario'))
                    ->view('emails.solicitud-permisos');
    }
}