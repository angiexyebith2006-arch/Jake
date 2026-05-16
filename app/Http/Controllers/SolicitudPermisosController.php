<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SolicitudPermisosMail;

class SolicitudPermisosController extends Controller
{
    public function create()
    {
        return view('solicitar-permisos');
    }

    public function store(Request $request)
    {
        $request->validate([
            'roles_solicitados' => 'required|array|min:1',
            'roles_solicitados.*' => 'string|in:admin,tesorero,lider,usuario',
            'permisos_adicionales' => 'nullable|array',
            'permisos_adicionales.*' => 'string',
            'justificacion' => 'required|string|min:10'
        ], [
            'roles_solicitados.required' => 'Debes seleccionar al menos un rol.',
            'roles_solicitados.min' => 'Debes seleccionar al menos un rol.',
            'justificacion.required' => 'La justificación es obligatoria.',
            'justificacion.min' => 'La justificación debe tener al menos 10 caracteres.'
        ]);

        $rolesSolicitados = $request->input('roles_solicitados', []);
        $permisosAdicionales = $request->input('permisos_adicionales', []);
        $justificacion = $request->input('justificacion', '');
        
        $usuario = session('usuario_api');
        
        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para solicitar permisos.');
        }
        
        // Mapeo de roles a nombres legibles y sus permisos base
        $mapaRoles = [
            'admin' => ['nombre' => '👑 Administrador', 'color' => '#8B5CF6', 'permisos' => ['programaciones', 'usuarios', 'roles', 'reportes', 'asistencia', 'finanzas', 'autorizaciones', 'chat_grupal']],
            'tesorero' => ['nombre' => '💰 Tesorero', 'color' => '#10B981', 'permisos' => ['finanzas', 'reportes', 'autorizaciones']],
            'lider' => ['nombre' => '⭐ Líder', 'color' => '#3B82F6', 'permisos' => ['programaciones', 'asistencia', 'chat_grupal']],
            'usuario' => ['nombre' => '👤 Usuario', 'color' => '#6B7280', 'permisos' => []]
        ];
        
        // Calcular permisos totales basados en roles seleccionados
        $permisosBaseTotales = [];
        $rolesConDetalles = [];
        
        foreach ($rolesSolicitados as $rol) {
            if (isset($mapaRoles[$rol])) {
                $permisosBaseTotales = array_merge($permisosBaseTotales, $mapaRoles[$rol]['permisos']);
                $rolesConDetalles[] = [
                    'id' => $rol,
                    'nombre' => $mapaRoles[$rol]['nombre'],
                    'color' => $mapaRoles[$rol]['color'],
                    'permisos' => $mapaRoles[$rol]['permisos'],
                    'permisos_nombres' => array_map(function($p) {
                        return $this->getNombrePermiso($p);
                    }, $mapaRoles[$rol]['permisos'])
                ];
            }
        }
        
        $permisosBaseTotales = array_unique($permisosBaseTotales);
        $todosLosPermisos = array_unique(array_merge($permisosBaseTotales, $permisosAdicionales));
        
        // Mapeo de permisos a nombres legibles
        $mapaPermisos = [
            'programaciones' => '📅 Programaciones',
            'usuarios' => '👥 Usuarios',
            'roles' => '⚙️ Roles y Permisos',
            'reportes' => '📊 Reportes',
            'asistencia' => '✅ Asistencia',
            'finanzas' => '💰 Finanzas',
            'autorizaciones' => '📝 Autorizaciones',
            'chat_grupal' => '💬 Chat Grupal'
        ];
        
        $datosSolicitud = [
            'roles_solicitados' => $rolesSolicitados,
            'roles_detalles' => $rolesConDetalles,
            'roles_nombres' => array_map(function($r) use ($mapaRoles) {
                return $mapaRoles[$r]['nombre'] ?? $r;
            }, $rolesSolicitados),
            'permisos_adicionales' => $permisosAdicionales,
            'permisos_adicionales_nombres' => array_map(function($p) use ($mapaPermisos) {
                return $mapaPermisos[$p] ?? ucfirst($p);
            }, $permisosAdicionales),
            'permisos_base_totales' => $permisosBaseTotales,
            'permisos_base_totales_nombres' => array_map(function($p) use ($mapaPermisos) {
                return $mapaPermisos[$p] ?? ucfirst($p);
            }, $permisosBaseTotales),
            'permisos_totales' => $todosLosPermisos,
            'permisos_totales_nombres' => array_map(function($p) use ($mapaPermisos) {
                return $mapaPermisos[$p] ?? ucfirst($p);
            }, $todosLosPermisos),
            'justificacion' => $justificacion,
            'fecha' => now()->format('d/m/Y H:i:s'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'total_roles' => count($rolesSolicitados),
            'total_modulos_base' => count($permisosBaseTotales),
            'total_modulos_adicionales' => count($permisosAdicionales),
            'total_modulos' => count($todosLosPermisos)
        ];
        
        // Guardar en log
        \Log::channel('daily')->info('📋 NUEVA SOLICITUD DE PERMISOS', [
            'usuario_nombre' => $usuario['nombre'] ?? 'Unknown',
            'usuario_id' => $usuario['id_usuario'] ?? 'Unknown',
            'usuario_correo' => $usuario['correo'] ?? 'Unknown',
            'roles_solicitados' => $rolesSolicitados,
            'modulos_base' => $permisosBaseTotales,
            'modulos_adicionales' => $permisosAdicionales,
            'total_modulos' => count($todosLosPermisos),
            'ip' => $request->ip(),
            'fecha' => now()->toDateTimeString()
        ]);
        
        // Obtener destinatarios desde .env (MAIL_ADMIN ya está configurado)
        $destinatarios = array_map('trim', explode(',', env('MAIL_ADMIN', 'javijunior820@gmail.com', 'angiexyebith2006@gmail.com', 'marafn.0529@gmail.com')));
        
        // También podemos agregar CC si existe MAIL_CC en .env
        $cc = [];
        if (env('MAIL_CC')) {
            $cc = array_map('trim', explode(',', env('MAIL_CC')));
        }
        
        // También podemos agregar BCC si existe MAIL_BCC en .env
        $bcc = [];
        if (env('MAIL_BCC')) {
            $bcc = array_map('trim', explode(',', env('MAIL_BCC')));
        }
        
        try {
            // Configurar el correo
            $mail = Mail::to($destinatarios);
            
            // Agregar CC si existen
            if (!empty($cc)) {
                $mail->cc($cc);
            }
            
            // Agregar BCC si existen
            if (!empty($bcc)) {
                $mail->bcc($bcc);
            }
            
            // Enviar el correo
            $mail->send(new SolicitudPermisosMail($datosSolicitud, $usuario));
            
            // También enviar una copia al solicitante
            if (isset($usuario['correo']) && !empty($usuario['correo'])) {
                Mail::to($usuario['correo'])
                    ->send(new SolicitudPermisosMail($datosSolicitud, $usuario));
            }
            
            \Log::info('✅ Solicitud enviada exitosamente a: ' . implode(', ', $destinatarios));
            
            return redirect()->route('perfil.show', $usuario['id_usuario'] ?? 4)
                ->with('success', '✅ Solicitud de ' . count($rolesSolicitados) . ' rol(es) y ' . count($permisosAdicionales) . ' módulo(s) adicional(es) enviada correctamente. Se ha notificado a los administradores.');
                
        } catch (\Exception $e) {
            \Log::error('❌ Error al enviar solicitud: ' . $e->getMessage());
            
            return redirect()->route('perfil.show', $usuario['id_usuario'] ?? 4)
                ->with('error', '❌ Error al enviar la solicitud: ' . $e->getMessage());
        }
    }
    
    private function getNombrePermiso($permiso)
    {
        $mapa = [
            'programaciones' => '📅 Programaciones',
            'usuarios' => '👥 Usuarios',
            'roles' => '⚙️ Roles y Permisos',
            'reportes' => '📊 Reportes',
            'asistencia' => '✅ Asistencia',
            'finanzas' => '💰 Finanzas',
            'autorizaciones' => '📝 Autorizaciones',
            'chat_grupal' => '💬 Chat Grupal'
        ];
        return $mapa[$permiso] ?? ucfirst($permiso);
    }
}