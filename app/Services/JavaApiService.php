<?php
// app/Services/JavaApiService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class JavaApiService
{
    private $client;
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('JAVA_API_BASE_URL', 'http://localhost:5431'), '/');
        $this->apiKey = env('JAVA_API_KEY', '');
        
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ];
        
        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl . '/api/',
            'timeout' => env('JAVA_API_TIMEOUT', 30),
            'headers' => $headers,
            'verify' => false,
            'http_errors' => false
        ]);
    }

    // ========== MÉTODO GENÉRICO ==========
    
    public function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $options = [];
            
            if (!empty($data)) {
                if ($method === 'GET') {
                    $options['query'] = $data;
                } else {
                    $options['json'] = $data;
                }
            }
            
            $response = $this->client->request($method, $endpoint, $options);
            
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);
            
            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $responseData,
                    'status' => $statusCode
                ];
            } else {
                $errorMessage = $responseData['message'] ?? 'Error en la petición';
                
                if ($statusCode >= 400 && $statusCode < 500) {
                    Log::warning("Java API Client Error $method $endpoint: $statusCode - $errorMessage");
                } else {
                    Log::error("Java API Server Error $method $endpoint: $statusCode - $errorMessage");
                }
                
                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'status' => $statusCode,
                    'details' => $responseData
                ];
            }
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::warning('Java API Connection Error: ' . $method . ' ' . $endpoint . ' - ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'No se pudo conectar con el servidor Java',
                'details' => 'Verifica que el servidor esté corriendo en ' . $this->baseUrl
            ];
            
        } catch (\Exception $e) {
            Log::error('Java API Error: ' . $method . ' ' . $endpoint . ' - ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Error en la petición',
                'details' => config('app.debug') ? $e->getMessage() : null
            ];
        }
    }

    // ========== MÉTODOS PARA PROGRAMACIONES ==========

    /**
     * Obtener todas las programaciones
     */
    public function getProgramaciones($filters = [])
    {
        return $this->makeRequest('GET', 'programaciones', $filters);
    }

    /**
     * Obtener una programación por ID
     */
    public function getProgramacion($id)
    {
        return $this->makeRequest('GET', "programaciones/{$id}");
    }

    /**
     * Obtener programaciones por día
     */
    public function getProgramacionesPorDia($dia)
    {
        return $this->makeRequest('GET', "programaciones/dia/{$dia}");
    }

    /**
     * Obtener programaciones por fecha
     */
    public function getProgramacionesPorFecha($fecha)
    {
        return $this->makeRequest('GET', "programaciones/fecha/{$fecha}");
    }

    /**
     * Obtener programaciones por estado
     */
    public function getProgramacionesPorEstado($estado)
    {
        return $this->makeRequest('GET', "programaciones/estado/{$estado}");
    }

    /**
     * Crear una nueva programación
     */
    public function createProgramacion($data)
    {
        return $this->makeRequest('POST', 'programaciones', $data);
    }

    /**
     * Actualizar una programación existente
     */
    public function updateProgramacion($id, $data)
    {
        return $this->makeRequest('PUT', "programaciones/{$id}", $data);
    }

    /**
     * Eliminar una programación
     */
    public function deleteProgramacion($id)
    {
        return $this->makeRequest('DELETE', "programaciones/{$id}");
    }

    /**
     * Confirmar una programación
     */
    public function confirmarProgramacion($id)
    {
        return $this->makeRequest('PUT', "programaciones/{$id}/confirmar");
    }

    /**
     * Cancelar confirmación de una programación
     */
    public function cancelarConfirmacionProgramacion($id)
    {
        return $this->makeRequest('PUT', "programaciones/{$id}/cancelar");
    }

    /**
     * Marcar programación como reemplazada
     */
    public function marcarReemplazada($id)
    {
        return $this->makeRequest('PUT', "programaciones/{$id}/reemplazada");
    }

    /**
     * Obtener estadísticas de programaciones
     */
    public function getEstadisticasProgramacion()
    {
        return $this->makeRequest('GET', 'programaciones/estadisticas');
    }

    // ========== MÉTODOS PARA PROGRAMACIONES DE USUARIO ==========

    /**
     * Obtener programaciones de un usuario
     */
    public function getProgramacionesUsuario($userId, $filters = [])
    {
        $endpoint = "programaciones/usuario/{$userId}";
        return $this->makeRequest('GET', $endpoint, $filters);
    }

    /**
     * Obtener una programación específica del usuario
     */
    public function getProgramacionUsuario($programacionId, $userId)
    {
        $endpoint = "programaciones/{$programacionId}/usuario/{$userId}";
        return $this->makeRequest('GET', $endpoint);
    }

    // ========== MÉTODOS PARA REEMPLAZOS ==========

    /**
     * Solicitar reemplazo para una programación
     */
    public function solicitarReemplazo($data)
    {
        return $this->makeRequest('POST', 'reemplazos/solicitar', $data);
    }

    /**
     * Obtener usuarios disponibles para reemplazo
     */
    public function getUsuariosReemplazo($programacionId, $excludeUserId = null)
    {
        $endpoint = "programaciones/{$programacionId}/usuarios-reemplazo";
        
        $params = [];
        if ($excludeUserId) {
            $params['exclude'] = $excludeUserId;
        }
        
        return $this->makeRequest('GET', $endpoint, $params);
    }

    /**
     * Obtener reemplazos pendientes del usuario
     */
    public function getReemplazosPendientes($userId)
    {
        $endpoint = "reemplazos/pendientes/{$userId}";
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Procesar (aprobar/rechazar) reemplazo
     */
    public function procesarReemplazo($reemplazoId, $data)
    {
        return $this->makeRequest('PUT', "reemplazos/{$reemplazoId}/procesar", $data);
    }

    // ========== MÉTODOS PARA ASIGNACIONES ==========

    /**
     * Obtener todas las asignaciones
     */
    public function getAsignaciones($filters = [])
    {
        return $this->makeRequest('GET', 'asignaciones', $filters);
    }

    /**
     * Obtener una asignación por ID
     */
    public function getAsignacion($id)
    {
        return $this->makeRequest('GET', "asignaciones/{$id}");
    }

    /**
     * Obtener asignaciones por usuario
     */
    public function getAsignacionesPorUsuario($userId)
    {
        return $this->makeRequest('GET', "asignaciones/usuario/{$userId}");
    }

    /**
     * Obtener asignaciones activas por usuario
     */
    public function getAsignacionesActivasPorUsuario($userId)
    {
        return $this->makeRequest('GET', "asignaciones/usuario/{$userId}/activas");
    }

    /**
     * Obtener asignaciones por ministerio
     */
    public function getAsignacionesPorMinisterio($ministerioId)
    {
        return $this->makeRequest('GET', "asignaciones/ministerio/{$ministerioId}");
    }

    /**
     * Obtener asignaciones activas por ministerio
     */
    public function getAsignacionesActivasPorMinisterio($ministerioId)
    {
        return $this->makeRequest('GET', "asignaciones/ministerio/{$ministerioId}/activas");
    }

    /**
     * Crear una nueva asignación
     */
    public function createAsignacion($data)
    {
        return $this->makeRequest('POST', 'asignaciones', $data);
    }

    /**
     * Actualizar una asignación existente
     */
    public function updateAsignacion($id, $data)
    {
        return $this->makeRequest('PUT', "asignaciones/{$id}", $data);
    }

    /**
     * Inactivar (eliminar lógico) una asignación
     */
    public function deleteAsignacion($id)
    {
        return $this->makeRequest('DELETE', "asignaciones/{$id}");
    }

    /**
     * Activar una asignación previamente inactivada
     */
    public function activarAsignacion($id)
    {
        return $this->makeRequest('PUT', "asignaciones/activar/{$id}");
    }

    /**
     * Eliminar permanentemente una asignación
     */
    public function deleteAsignacionPermanente($id)
    {
        return $this->makeRequest('DELETE', "asignaciones/permanente/{$id}");
    }

    /**
     * Contar asignaciones activas por ministerio
     */
    public function contarAsignacionesActivasPorMinisterio($ministerioId)
    {
        $response = $this->makeRequest('GET', "asignaciones/ministerio/{$ministerioId}/count");
        
        if ($response['success']) {
            return [
                'success' => true,
                'count' => $response['data'] ?? 0
            ];
        }
        
        return $response;
    }

    /**
     * Obtener asignaciones con detalles completos
     */
    public function getAsignacionesCompletas($filters = [])
    {
        return $this->makeRequest('GET', 'asignaciones/completas', $filters);
    }

    /**
     * Obtener asignaciones por ministerio con detalles
     */
    public function getAsignacionesPorMinisterioCompleto($ministerioId)
    {
        return $this->makeRequest('GET', "asignaciones/ministerio/{$ministerioId}/completo");
    }

    /**
     * Obtener todas las asignaciones con paginación
     */
    public function getAsignacionesPaginadas($page = 1, $size = 20, $filters = [])
    {
        $data = array_merge([
            'page' => $page,
            'size' => $size
        ], $filters);
        
        return $this->makeRequest('GET', 'asignaciones/paginadas', $data);
    }

    // ========== MÉTODOS PARA DATOS MAESTROS ==========
    
    /**
     * Obtener todos los ministerios
     */
    public function getMinisterios()
    {
        try {
            $cacheKey = 'java_ministerios';
            
            if (Cache::has($cacheKey)) {
                $data = Cache::get($cacheKey);
                return [
                    'success' => true, 
                    'data' => $data,
                    'cached' => true
                ];
            }
            
            $response = $this->makeRequest('GET', 'ministerios');
            
            if ($response['success']) {
                Cache::put($cacheKey, $response['data'], 3600);
                $response['cached'] = false;
            }
            
            return $response;
            
        } catch (\Exception $e) {
            return [
                'success' => false, 
                'error' => 'Error al obtener ministerios: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener todas las actividades
     */
    public function getActividades()
    {
        return $this->makeRequest('GET', 'actividades');
    }
    
    /**
     * Obtener todos los roles
     */
    public function getRoles()
    {
        return $this->makeRequest('GET', 'roles');
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function getUsuarios($filters = [])
    {
        return $this->makeRequest('GET', 'usuarios', $filters);
    }
    
    /**
     * Obtener un usuario por ID
     */
    public function getUsuario($id)
    {
        return $this->makeRequest('GET', "usuarios/{$id}");
    }
    
    /**
     * Obtener usuarios activos
     */
    public function getUsuariosActivos()
    {
        return $this->makeRequest('GET', 'usuarios/activos');
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getUsuariosPorRol($rolId)
    {
        return $this->makeRequest('GET', "usuarios/rol/{$rolId}");
    }
    
    /**
     * Obtener usuarios por ministerio
     */
    public function getUsuariosPorMinisterio($ministerioId)
    {
        return $this->makeRequest('GET', "usuarios/ministerio/{$ministerioId}");
    }

    // ========== MÉTODOS PARA AUTORIZACIONES ==========

    /**
     * Obtener autorizaciones pendientes
     */
    public function getAutorizacionesPendientes($autorizadorId)
    {
        $endpoint = "autorizaciones/pendientes/{$autorizadorId}";
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Aprobar autorización
     */
    public function aprobarAutorizacion($autorizacionId, $data = [])
    {
        return $this->makeRequest('PUT', "autorizaciones/{$autorizacionId}/aprobar", $data);
    }

    /**
     * Rechazar autorización
     */
    public function rechazarAutorizacion($autorizacionId, $data = [])
    {
        return $this->makeRequest('PUT', "autorizaciones/{$autorizacionId}/rechazar", $data);
    }

    // ========== MÉTODOS ADICIONALES ==========

    /**
     * Obtener estadísticas del usuario
     */
    public function getEstadisticasUsuario($userId)
    {
        $endpoint = "usuarios/{$userId}/estadisticas";
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Obtener historial de asistencia del usuario
     */
    public function getHistorialAsistencia($userId, $filters = [])
    {
        $endpoint = "usuarios/{$userId}/historial-asistencia";
        return $this->makeRequest('GET', $endpoint, $filters);
    }

    /**
     * Verificar disponibilidad para reemplazo
     */
    public function verificarDisponibilidad($userId, $fecha, $horaInicio, $horaFin)
    {
        $endpoint = "usuarios/{$userId}/disponibilidad";
        
        $data = [
            'fecha' => $fecha,
            'horaInicio' => $horaInicio,
            'horaFin' => $horaFin
        ];
        
        return $this->makeRequest('POST', $endpoint, $data);
    }

    /**
     * Obtener calendario del usuario
     */
    public function getCalendarioUsuario($userId, $mes = null, $anio = null)
    {
        $endpoint = "usuarios/{$userId}/calendario";
        
        $params = [];
        if ($mes) $params['mes'] = $mes;
        if ($anio) $params['anio'] = $anio;
        
        return $this->makeRequest('GET', $endpoint, $params);
    }

    // ========== MÉTODOS PARA REPORTES ==========
    
    /**
     * Obtener reporte de asistencias
     */
    public function getReporteAsistencias($filters = [])
    {
        return $this->makeRequest('GET', 'reportes/asistencias', $filters);
    }
    
    /**
     * Obtener reporte de reemplazos
     */
    public function getReporteReemplazos($filters = [])
    {
        return $this->makeRequest('GET', 'reportes/reemplazos', $filters);
    }
    
    /**
     * Obtener reporte por ministerios
     */
    public function getReporteMinisterios($filters = [])
    {
        return $this->makeRequest('GET', 'reportes/ministerios', $filters);
    }
    
    /**
     * Obtener reporte por usuarios
     */
    public function getReporteUsuarios($filters = [])
    {
        return $this->makeRequest('GET', 'reportes/usuarios', $filters);
    }

    // ========== MÉTODOS PARA MONITOREO ==========
    
    /**
     * Verificar salud de la API
     */
    public function checkHealth()
    {
        try {
            $response = $this->client->get('actuator/health', ['timeout' => 10]);
            $data = json_decode($response->getBody(), true);
            return [
                'success' => true,
                'status' => $data['status'] ?? 'UNKNOWN',
                'details' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'DOWN',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar estado del sistema
     */
    public function getSystemStatus()
    {
        try {
            $response = $this->client->get('actuator/metrics', ['timeout' => 10]);
            $data = json_decode($response->getBody(), true);
            return [
                'success' => true,
                'status' => 'UP',
                'metrics' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'DOWN',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener información de la aplicación
     */
    public function getAppInfo()
    {
        try {
            $response = $this->client->get('actuator/info', ['timeout' => 10]);
            $data = json_decode($response->getBody(), true);
            return [
                'success' => true,
                'info' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}