<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia y Reemplazo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Main Content -->
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Asistencia y Reemplazo</h1>
                <p class="text-gray-600">Confirma tu asistencia o solicita reemplazo</p>
            </div>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <button id="filterBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-50 transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
                <button id="refreshBtn" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
            </div>
        </div>

        <!-- Panel superior informativo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Tarjeta de estadísticas -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl rounded-2xl border border-blue-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-chart-bar text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Resumen</h2>
                            <p class="text-blue-100 text-sm">Estadísticas</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Total</p>
                            <p class="text-white text-xl font-bold">{{ $programaciones->count() }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Confirmadas</p>
                            <p class="text-white text-xl font-bold">{{ $programaciones->where('estado', 'Confirmado')->count() }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Pendientes</p>
                            <p class="text-white text-xl font-bold">{{ $programaciones->where('estado', 'Pendiente')->count() }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Reemplazos</p>
                            <p class="text-white text-xl font-bold">{{ $programaciones->where('estado', 'Reemplazado')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta de usuario -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-700 shadow-xl rounded-2xl border border-purple-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Usuario Activo</h2>
                            <p class="text-purple-100 text-sm">Información</p>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 p-3 rounded-lg">
                        @if(auth()->check())
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mr-3 text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">{{ auth()->user()->name }}</p>
                                <p class="text-purple-100 text-xs">Servidor activo</p>
                            </div>
                        </div>
                        @else
                        <p class="text-purple-100">No autenticado</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta de acciones rápidas -->
            <div class="md:col-span-2 bg-gradient-to-r from-green-600 to-teal-700 shadow-xl rounded-2xl border border-green-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-bolt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Acciones Rápidas</h2>
                            <p class="text-green-100 text-sm">Gestión rápida</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="abrirTodasConfirmaciones()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-check-double mr-2"></i> Confirmar Todo
                        </button>
                        <button onclick="verHistorial()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-history mr-2"></i> Ver Historial
                        </button>
                        <a href="{{ route('autorizaciones.index') }}" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-clipboard-check mr-2"></i> Autorizaciones
                        </a>
                        <button onclick="mostrarAyuda()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-question-circle mr-2"></i> Ayuda
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla principal (ancho completo) -->
        <div class="w-full">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Programaciones Activas</h2>
                            <p class="text-gray-600 text-sm">Gestiona tu asistencia a las actividades programadas</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Mostrando {{ $programaciones->count() }} registros
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($programaciones->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user mr-2 text-blue-500"></i>Servidor
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-calendar mr-2 text-purple-500"></i>Fecha
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-clock mr-2 text-orange-500"></i>Horario
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-hands-praying mr-2 text-green-500"></i>Actividad
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user-tag mr-2 text-red-500"></i>Rol
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-info-circle mr-2 text-gray-500"></i>Estado
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-cog mr-2 text-gray-500"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($programaciones as $programacion)
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                    {{ substr($programacion->asignacion->usuario->nombre, 0, 2) }}
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $programacion->asignacion->usuario->nombre }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($programacion->fecha)->format('d/m/Y') }}</span>
                                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($programacion->fecha)->translatedFormat('l') }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-gray-700">{{ $programacion->hora_inicio }} - {{ $programacion->hora_fin }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-700 font-medium">{{ $programacion->actividad->nombre_actividad }}</span>
                                                <span class="text-xs text-gray-500">{{ $programacion->actividad->ministerio->nombre_ministerio }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $programacion->asignacion->rol->nombre_rol }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <span class="{{ 
                                                $programacion->estado == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($programacion->estado == 'Confirmado' ? 'bg-green-100 text-green-800' : 
                                                'bg-red-100 text-red-800') 
                                            }} px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $programacion->estado }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            @if($programacion->estado == 'Confirmado')
                                                <span class="bg-green-100 text-green-800 px-3 py-2 rounded-xl text-xs font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i>Asistencia Confirmada
                                                </span>
                                            @elseif($programacion->estado == 'Reemplazado')
                                                <span class="bg-red-100 text-red-800 px-3 py-2 rounded-xl text-xs font-semibold">
                                                    <i class="fas fa-exchange-alt mr-1"></i>Reemplazo Solicitado
                                                </span>
                                            @else
                                                <div class="flex space-x-2">
                                                    <button onclick="confirmarAsistencia({{ $programacion->id_programacion }})" class="confirm-btn bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                        <i class="fas fa-check mr-1"></i>Confirmar
                                                    </button>
                                                    <button onclick="abrirModalReemplazo({{ $programacion->id_programacion }})" class="replace-btn bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                        <i class="fas fa-exchange-alt mr-1"></i>Reemplazo
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Estado vacío -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay programaciones</h3>
                        <p class="text-gray-500 max-w-md mx-auto">No tienes actividades programadas para las fechas seleccionadas.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="mt-4">
            <div id="apiStatus" class="hidden p-3 rounded-xl bg-gray-50 border border-gray-200">
                <!-- Se llenará con JavaScript -->
            </div>
        </div>

        <!-- Filtros -->
        <div id="filterSection" class="bg-white p-6 rounded-2xl shadow-lg mt-6 border border-gray-200 hidden">
            <form method="GET" action="{{ route('asistencia.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ministerio</label>
                        <select name="ministerio" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los ministerios</option>
                            @foreach($ministerios as $ministerio)
                                <option value="{{ $ministerio->id_ministerio }}" {{ request('ministerio') == $ministerio->id_ministerio ? 'selected' : '' }}>
                                    {{ $ministerio->nombre_ministerio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Confirmado" {{ request('estado') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="Reemplazado" {{ request('estado') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-3">
                    <a href="{{ route('asistencia.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Limpiar
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Modal de Reemplazo -->
    <div id="reemplazoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Solicitar Reemplazo</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="modalContent">
                <!-- Contenido dinámico del modal -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancelReemplazo" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button id="submitReemplazo" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                    Solicitar Reemplazo
                </button>
            </div>
        </div>
    </div>

    <script>
        // Estado actual
        let currentProgramacionId = null;

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            inicializarEventListeners();
            verificarApiStatus();
        });

        function inicializarEventListeners() {
            // Filtros
            document.getElementById('filterBtn').addEventListener('click', function() {
                document.getElementById('filterSection').classList.toggle('hidden');
            });
            
            // Actualizar
            document.getElementById('refreshBtn').addEventListener('click', function() {
                location.reload();
            });
            
            // Modal de reemplazo
            document.getElementById('closeModal').addEventListener('click', cerrarModalReemplazo);
            document.getElementById('cancelReemplazo').addEventListener('click', cerrarModalReemplazo);
            document.getElementById('submitReemplazo').addEventListener('click', solicitarReemplazo);
            
            // Cerrar modal al hacer click fuera
            document.getElementById('reemplazoModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    cerrarModalReemplazo();
                }
            });
        }

        function verificarApiStatus() {
            fetch('/asistencia/api/verificar')
                .then(response => response.json())
                .then(data => {
                    const apiStatus = document.getElementById('apiStatus');
                    apiStatus.classList.remove('hidden');
                    
                    if (data.status === 'connected') {
                        apiStatus.className = 'bg-green-100 text-green-800 p-3 rounded-xl border border-green-200';
                        apiStatus.innerHTML = '<i class="fas fa-check-circle"></i> Conectado a API Java';
                    } else if (data.status === 'local') {
                        apiStatus.className = 'bg-blue-100 text-blue-800 p-3 rounded-xl border border-blue-200';
                        apiStatus.innerHTML = '<i class="fas fa-database"></i> Usando base de datos local';
                    } else {
                        apiStatus.className = 'bg-red-100 text-red-800 p-3 rounded-xl border border-red-200';
                        apiStatus.innerHTML = `<i class="fas fa-exclamation-circle"></i> Error API: ${data.message}`;
                    }
                })
                .catch(error => {
                    console.error('Error verificando API:', error);
                });
        }

        function confirmarAsistencia(programacionId) {
            if (confirm('¿Confirmar asistencia para esta actividad?')) {
                fetch(`/asistencia/${programacionId}/confirmar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('chatgrupal.index') }}";
                        }, 1000);
                    } else {
                        mostrarMensaje(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje('Error al confirmar asistencia', 'error');
                });
            }
        }

        function abrirModalReemplazo(programacionId) {
            currentProgramacionId = programacionId;
            
            fetch(`/asistencia/${programacionId}`)
                .then(response => response.json())
                .then(programacion => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h4 class="font-semibold text-gray-800 mb-2">Detalles de la programación</h4>
                                <p class="text-sm text-gray-600">
                                    <strong>Servidor:</strong> ${programacion.asignacion.usuario.nombre}<br>
                                    <strong>Actividad:</strong> ${programacion.actividad.nombre_actividad}<br>
                                    <strong>Fecha:</strong> ${new Date(programacion.fecha).toLocaleDateString('es-ES')}<br>
                                    <strong>Horario:</strong> ${programacion.hora_inicio} - ${programacion.hora_fin}<br>
                                    <strong>Rol:</strong> ${programacion.asignacion.rol.nombre_rol}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del reemplazo *</label>
                                <textarea id="motivoReemplazo" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Describe el motivo de tu solicitud de reemplazo..." required></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sugerir reemplazo (opcional)</label>
                                <select id="sugerirReemplazo" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccionar servidor...</option>
                                    @foreach($servidores as $servidor)
                                        <option value="{{ $servidor->id_usuario }}">{{ $servidor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('reemplazoModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje('Error al cargar datos de la programación', 'error');
                });
        }

        function solicitarReemplazo() {
            const motivo = document.getElementById('motivoReemplazo').value.trim();
            const servidorReemplazo = document.getElementById('sugerirReemplazo').value;
            
            if (!motivo) {
                mostrarMensaje('Por favor, ingresa un motivo para el reemplazo', 'error');
                return;
            }
            
            // Mostrar loading
            const submitBtn = document.getElementById('submitReemplazo');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            submitBtn.disabled = true;
            
            fetch(`/asistencia/${currentProgramacionId}/solicitar-reemplazo`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new URLSearchParams({
                    motivo: motivo,
                    id_usuario_reemplazo: servidorReemplazo || ''
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error en la respuesta del servidor');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModalReemplazo();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje(data.message || 'Error desconocido', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al solicitar reemplazo: ' + error.message, 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Funciones auxiliares
        function abrirTodasConfirmaciones() {
            if (confirm('¿Confirmar asistencia para todas las programaciones pendientes?')) {
                mostrarMensaje('Funcionalidad en desarrollo', 'info');
            }
        }

        function verHistorial() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function mostrarAyuda() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        // Manejar mensajes de Laravel
        @if(session('success'))
            setTimeout(() => {
                mostrarMensaje('{{ session('success') }}', 'success');
            }, 100);
        @endif

        @if(session('error'))
            setTimeout(() => {
                mostrarMensaje('{{ session('error') }}', 'error');
            }, 100);
        @endif

        // Función para mostrar mensajes
        function mostrarMensaje(mensaje, tipo) {
            // Crear contenedor de mensaje si no existe
            let mensajeContainer = document.getElementById('mensajeContainer');
            if (!mensajeContainer) {
                mensajeContainer = document.createElement('div');
                mensajeContainer.id = 'mensajeContainer';
                mensajeContainer.className = 'fixed top-4 right-4 z-50';
                document.body.appendChild(mensajeContainer);
            }

            // Crear mensaje
            const alertDiv = document.createElement('div');
            alertDiv.className = `p-4 mb-4 rounded-lg shadow-lg ${tipo === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 
                                tipo === 'error' ? 'bg-red-100 text-red-800 border border-red-300' : 
                                'bg-blue-100 text-blue-800 border border-blue-300'}`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 
                                   tipo === 'error' ? 'fa-exclamation-circle' : 
                                   'fa-info-circle'} mr-3"></i>
                    <span class="font-medium">${mensaje}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            mensajeContainer.appendChild(alertDiv);

            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Función para cerrar modal de reemplazo
        function cerrarModalReemplazo() {
            document.getElementById('reemplazoModal').classList.add('hidden');
            currentProgramacionId = null;
            document.getElementById('modalContent').innerHTML = '';
        }

    </script>
</body>
</html>
</x-app-layout>