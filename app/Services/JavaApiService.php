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
        // Configurar desde .env
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
            'http_errors' => false // No lanzar excepciones por códigos HTTP 4xx/5xx
        ]);
    }

    // ========== MÉTODO GENÉRICO PARA TODAS LAS PETICIONES ==========
    
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

    // ========== MÉTODOS PARA PROGRAMACIÓN ==========
    
    public function getProgramaciones($filters = []) { 
        return $this->makeRequest('GET', 'programaciones', $filters); 
    }
    
    public function getProgramacion($id) { 
        return $this->makeRequest('GET', 'programaciones/' . $id); 
    }
    
    public function getProgramacionesPorDia($dia) { 
        return $this->makeRequest('GET', 'programaciones/dia/' . $dia); 
    }
    
    public function getProgramacionesPorFecha($fecha) { 
        return $this->makeRequest('GET', 'programaciones/fecha/' . $fecha); 
    }
    
    public function getProgramacionesPorEstado($estado) { 
        return $this->makeRequest('GET', 'programaciones/estado/' . $estado); 
    }
    
    public function createProgramacion($data) { 
        return $this->makeRequest('POST', 'programaciones', $data); 
    }
    
    public function updateProgramacion($id, $data) { 
        return $this->makeRequest('PUT', 'programaciones/' . $id, $data); 
    }
    
    public function deleteProgramacion($id) { 
        return $this->makeRequest('DELETE', 'programaciones/' . $id); 
    }
    
    public function getEstadisticasProgramacion() { 
        return $this->makeRequest('GET', 'programaciones/estadisticas'); 
    }

    // ========== MÉTODOS PARA ASIGNACIONES ==========
    
    public function createAsignacion($data) { 
        return $this->makeRequest('POST', 'asignaciones', $data); 
    }
    
    public function updateAsignacion($id, $data) { 
        return $this->makeRequest('PUT', 'asignaciones/' . $id, $data); 
    }
    
    public function deleteAsignacion($id) { 
        return $this->makeRequest('DELETE', 'asignaciones/' . $id); 
    }

    // ========== MÉTODOS PARA DATOS MAESTROS ==========
    
    public function getMinisterios() { 
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
            
            $response = $this->client->get('ministerios');
            
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                Cache::put($cacheKey, $data, 3600);
                
                return [
                    'success' => true, 
                    'data' => $data,
                    'cached' => false
                ];
            } else {
                return [
                    'success' => false, 
                    'error' => 'Error al obtener ministerios: ' . $response->getStatusCode()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false, 
                'error' => 'Error al obtener ministerios: ' . $e->getMessage()
            ];
        }
    }
    
    public function getActividades() { 
        return $this->makeRequest('GET', 'actividades'); 
    }
    
    public function getRoles() { 
        return $this->makeRequest('GET', 'roles'); 
    }
    
    public function getServidores($excludeUserId = null) { 
        $query = $excludeUserId ? ['exclude' => $excludeUserId] : [];
        return $this->makeRequest('GET', 'usuarios/activos', $query);
    }



    // ========== MÉTODOS PARA MONITOREO ==========
    
    public function checkHealth() {
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
}