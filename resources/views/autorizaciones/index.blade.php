<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizaciones Pendientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Main Content -->
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-clipboard-check mr-2 text-blue-500"></i>
                    Autorizaciones Pendientes
                </h1>
                <p class="text-gray-600">Gestiona las solicitudes de reemplazo pendientes de aprobación</p>
            </div>
            <div class="flex space-x-4 mt-4 sm:mt-0">
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
                            <p class="text-blue-100 text-xs">Total Pendientes</p>
                            <p class="text-white text-xl font-bold">{{ $autorizaciones->count() }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Por Aprobar</p>
                            <p class="text-white text-xl font-bold">{{ $autorizaciones->where('estado', 'Pendiente')->count() }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Aprobadas Hoy</p>
                            <p class="text-white text-xl font-bold">0</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Rechazadas</p>
                            <p class="text-white text-xl font-bold">0</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta de autorizador -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-700 shadow-xl rounded-2xl border border-purple-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Autorizador</h2>
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
                                <p class="text-purple-100 text-xs">Nivel de autorización</p>
                            </div>
                        </div>
                        @else
                        <p class="text-purple-100 text-sm">No autenticado</p>
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
                        <a href="{{ route('asistencia.index') }}" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <button onclick="verHistorialAutorizaciones()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-history mr-2"></i> Historial
                        </button>
                        <button onclick="aprobarTodas()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-check-double mr-2"></i> Aprobar Todo
                        </button>
                        <button onclick="exportarReporte()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-file-export mr-2"></i> Exportar
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
                            <h2 class="text-xl font-bold text-gray-800">Solicitudes de Reemplazo</h2>
                            <p class="text-gray-600 text-sm">Revisa y gestiona las autorizaciones pendientes</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-list-alt mr-1"></i>
                                Mostrando {{ $autorizaciones->count() }} registros
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if(!$autorizaciones->isEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Programación
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user mr-2 text-purple-500"></i>Servidor Original
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user-plus mr-2 text-green-500"></i>Servidor Reemplazo
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-comment mr-2 text-orange-500"></i>Motivo
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user-tie mr-2 text-red-500"></i>Autorizador
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-cog mr-2 text-gray-500"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($autorizaciones as $autorizacion)
                                    @if($autorizacion->reemplazo && $autorizacion->reemplazo->programacion)
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-700 font-medium">
                                                    {{ $autorizacion->reemplazo->programacion->actividad->nombre_actividad ?? 'Sin actividad' }}
                                                </span>
                                                <div class="flex items-center mt-1">
                                                    <i class="fas fa-calendar-day text-gray-400 text-xs mr-1"></i>
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($autorizacion->reemplazo->programacion->fecha)->format('d/m/Y') }}
                                                    </span>
                                                    <span class="mx-2 text-gray-300">|</span>
                                                    <i class="fas fa-clock text-gray-400 text-xs mr-1"></i>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $autorizacion->reemplazo->programacion->hora_inicio }} - {{ $autorizacion->reemplazo->programacion->hora_fin }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @if($autorizacion->reemplazo->asignacionReemplazado && $autorizacion->reemplazo->asignacionReemplazado->usuario)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ substr($autorizacion->reemplazo->asignacionReemplazado->usuario->nombre, 0, 2) }}
                                                </div>
                                                <span class="text-gray-700">{{ $autorizacion->reemplazo->asignacionReemplazado->usuario->nombre }}</span>
                                            </div>
                                            @else
                                            <span class="text-gray-400 italic">No encontrado</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($autorizacion->reemplazo->asignacionReemplazoPor && $autorizacion->reemplazo->asignacionReemplazoPor->usuario)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ substr($autorizacion->reemplazo->asignacionReemplazoPor->usuario->nombre, 0, 2) }}
                                                </div>
                                                <span class="text-gray-700">{{ $autorizacion->reemplazo->asignacionReemplazoPor->usuario->nombre }}</span>
                                            </div>
                                            @else
                                            <span class="text-gray-400 italic">No sugerido</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="max-w-xs">
                                                <span class="text-gray-700 text-sm line-clamp-2">
                                                    {{ $autorizacion->reemplazo->motivo ?? 'Sin motivo especificado' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @if($autorizacion->autorizador)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ substr($autorizacion->autorizador->nombre, 0, 2) }}
                                                </div>
                                                <span class="text-gray-700 text-sm">{{ $autorizacion->autorizador->nombre }}</span>
                                            </div>
                                            @else
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                Por asignar
                                            </span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <button onclick="aprobarAutorizacion({{ $autorizacion->id_autorizacion }})" 
                                                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                    <i class="fas fa-check mr-1"></i>Aprobar
                                                </button>
                                                <button onclick="rechazarAutorizacion({{ $autorizacion->id_autorizacion }})" 
                                                        class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                    <i class="fas fa-times mr-1"></i>Rechazar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Estado vacío -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-r from-green-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">¡Todo al día!</h3>
                        <p class="text-gray-500 max-w-md mx-auto">No hay autorizaciones pendientes en este momento.</p>
                        <div class="mt-6">
                            <a href="{{ route('asistencia.index') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Volver a Asistencia
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para observaciones -->
    <div id="observacionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800"></h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                <p id="modalDescription" class="text-gray-600 mb-4"></p>
                <textarea id="observacionesInput" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          rows="4" placeholder="Ingresa observaciones..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="cerrarModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button id="modalConfirmBtn" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentAutorizacionId = null;
        let currentAction = '';

        // Inicializar eventos
        document.addEventListener('DOMContentLoaded', function() {
            // Botón de actualizar
            document.getElementById('refreshBtn').addEventListener('click', function() {
                location.reload();
            });

            // Manejar mensajes de Laravel
            @if(session('success'))
                mostrarMensaje('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                mostrarMensaje('{{ session('error') }}', 'error');
            @endif
        });

        function aprobarAutorizacion(id) {
            currentAutorizacionId = id;
            currentAction = 'aprobar';
            document.getElementById('modalTitle').textContent = 'Aprobar Autorización';
            document.getElementById('modalDescription').textContent = '¿Estás seguro de aprobar esta solicitud de reemplazo? Puedes agregar observaciones opcionales.';
            document.getElementById('observacionesInput').placeholder = 'Observaciones (opcional)...';
            document.getElementById('observacionModal').classList.remove('hidden');
        }

        function rechazarAutorizacion(id) {
            currentAutorizacionId = id;
            currentAction = 'rechazar';
            document.getElementById('modalTitle').textContent = 'Rechazar Autorización';
            document.getElementById('modalDescription').textContent = '¿Estás seguro de rechazar esta solicitud de reemplazo? Debes incluir el motivo del rechazo.';
            document.getElementById('observacionesInput').placeholder = 'Motivo del rechazo (requerido)...';
            document.getElementById('observacionModal').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('observacionModal').classList.add('hidden');
            document.getElementById('observacionesInput').value = '';
            currentAutorizacionId = null;
            currentAction = '';
        }

        // Configurar botón de confirmación del modal
        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            const observaciones = document.getElementById('observacionesInput').value;
            
            if (currentAction === 'rechazar' && !observaciones.trim()) {
                mostrarMensaje('Debes ingresar un motivo para rechazar', 'error');
                return;
            }

            const url = currentAction === 'aprobar' 
                ? `/autorizaciones/${currentAutorizacionId}/aprobar`
                : `/autorizaciones/${currentAutorizacionId}/rechazar`;

            // Mostrar loading
            const confirmBtn = document.getElementById('modalConfirmBtn');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            confirmBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    observaciones: observaciones.trim()
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModal();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje('Error: ' + data.message, 'error');
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al procesar la solicitud', 'error');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
                cerrarModal();
            });
        });

        // Cerrar modal al hacer click fuera
        document.getElementById('observacionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Funciones auxiliares para las nuevas acciones
        function aprobarTodas() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function exportarReporte() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function verHistorialAutorizaciones() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

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
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>
</x-app-layout>