<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Programaciones</title>
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
                    <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                    Mis Programaciones
                </h1>
                <p class="text-gray-600">Confirma tu asistencia o solicita reemplazo</p>
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
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Resumen</h2>
                            <p class="text-blue-100 text-sm">Estadísticas</p>
                        </div>
                    </div>
                    
                    @php
                        $totalProgramaciones = 0;
                        $confirmadas = 0;
                        $pendientes = 0;
                        $reemplazosSolicitados = 0;
                        
                        foreach($asignaciones as $a) {
                            $totalProgramaciones += $a->programaciones->count();
                            $confirmadas += $a->programaciones->where('estado', 'Confirmado')->count();
                            $pendientes += $a->programaciones->where('estado', '!=', 'Confirmado')->count();
                        }
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Total</p>
                            <p class="text-white text-xl font-bold">{{ $totalProgramaciones }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Confirmadas</p>
                            <p class="text-white text-xl font-bold">{{ $confirmadas }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Pendientes</p>
                            <p class="text-white text-xl font-bold">{{ $pendientes }}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-lg">
                            <p class="text-blue-100 text-xs">Roles</p>
                            <p class="text-white text-xl font-bold">{{ $asignaciones->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta de usuario -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-700 shadow-xl rounded-2xl border border-purple-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-user-circle text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Servidor</h2>
                            <p class="text-purple-100 text-sm">Información</p>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 p-3 rounded-lg">
                        @if(isset($asignaciones[0]))
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($asignaciones[0]->nombre_usuario, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">{{ $asignaciones[0]->nombre_usuario }}</p>
                                <p class="text-purple-100 text-xs">{{ $asignaciones->count() }} roles asignados</p>
                            </div>
                        </div>
                        @else
                        <p class="text-purple-100 text-sm">Sin asignaciones</p>
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
                        <button onclick="confirmarTodas()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-check-double mr-2"></i> Confirmar Todo
                        </button>
                        <button onclick="verCalendario()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-calendar-alt mr-2"></i> Calendario
                        </button>
                        <button onclick="exportarReporte()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-file-export mr-2"></i> Exportar
                        </button>
                        <button onclick="historialAsistencia()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl flex items-center justify-center transition-colors text-sm">
                            <i class="fas fa-history mr-2"></i> Historial
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de asignaciones -->
        @foreach($asignaciones as $a)
        <div class="mb-6 bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- CABECERA ASIGNACIÓN -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-white">
                            {{ $a->nombre_usuario }}
                        </h2>
                        <div class="flex items-center mt-1">
                            <span class="text-sm text-blue-100">
                                <i class="fas fa-church mr-1"></i>
                                {{ $a->nombre_ministerio }}
                            </span>
                            <span class="mx-3 text-blue-200">|</span>
                            <span class="text-sm text-blue-100">
                                <i class="fas fa-user-tag mr-1"></i>
                                {{ $a->nombre_rol }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm">
                            {{ $a->programaciones->count() }} programaciones
                        </span>
                    </div>
                </div>
            </div>

            <!-- CONTENIDO -->
            <div class="p-6">
                @if($a->programaciones->isEmpty())
                    <!-- Estado vacío -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-gray-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Sin programaciones</h3>
                        <p class="text-gray-500 max-w-md mx-auto">No hay programaciones asignadas para este rol.</p>
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-tasks mr-2 text-blue-500"></i>Actividad
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-calendar-day mr-2 text-purple-500"></i>Fecha y Hora
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Estado
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-cogs mr-2 text-orange-500"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($a->programaciones as $p)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="text-gray-700 font-medium">
                                            {{ $p->nombre_actividad }}
                                        </span>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-info-circle text-gray-400 text-xs mr-1"></i>
                                            <span class="text-xs text-gray-500">
                                                ID: {{ $p->id_programacion }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar text-gray-400 text-xs mr-2"></i>
                                            <span class="text-gray-700">
                                                {{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <i class="fas fa-clock text-gray-400 text-xs mr-2"></i>
                                            <span class="text-gray-700">
                                                {{ $p->hora_inicio }} - {{ $p->hora_fin }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($p->estado === 'Confirmado')
                                    <span class="inline-flex items-center bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check mr-1"></i> Confirmado
                                    </span>
                                    @else
                                    <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full text-xs font-semibold">
                                        <i class="fas fa-clock mr-1"></i> Pendiente
                                    </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        {{-- CONFIRMAR --}}
                                        @if(!$p->confirmado)
                                        <form method="POST"
                                              action="{{ route('asistencia.confirmar', $p->id_programacion) }}"
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i> Confirmar
                                            </button>
                                        </form>
                                        @endif

                                        {{-- REEMPLAZO --}}
                                        <form method="POST"
                                              action="{{ route('asistencia.reemplazo') }}"
                                              class="inline">
                                            @csrf
                                            <input type="hidden" name="id_programacion" value="{{ $p->id_programacion }}">
                                            <input type="hidden" name="id_asignacion_reemplazo_por" value="{{ $a->id_asignacion }}">
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                                <i class="fas fa-user-exchange mr-1"></i> Reemplazo
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        @if($asignaciones->isEmpty())
        <!-- Estado vacío general -->
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-plus text-blue-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Sin asignaciones</h3>
            <p class="text-gray-500 max-w-md mx-auto">No tienes asignaciones de programaciones en este momento.</p>
            <div class="mt-6">
                <button onclick="solicitarAsignacion()" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                    <i class="fas fa-plus-circle mr-2"></i> Solicitar Asignación
                </button>
            </div>
        </div>
        @endif
    </main>

    <!-- Modal para reemplazo -->
    <div id="reemplazoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Solicitar Reemplazo</h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-gray-600 mb-4">¿Estás seguro de solicitar un reemplazo para esta programación?</p>
                <textarea id="motivoReemplazo" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          rows="4" placeholder="Ingresa el motivo del reemplazo (opcional)..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="cerrarModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button id="confirmarReemplazoBtn" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-300">
                    <i class="fas fa-user-exchange mr-1"></i> Solicitar Reemplazo
                </button>
            </div>
        </div>
    </div>

    <script>
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

        let currentReemplazoForm = null;

        // Interceptar formularios de reemplazo
        document.querySelectorAll('form[action*="reemplazo"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                currentReemplazoForm = this;
                document.getElementById('reemplazoModal').classList.remove('hidden');
            });
        });

        function cerrarModal() {
            document.getElementById('reemplazoModal').classList.add('hidden');
            document.getElementById('motivoReemplazo').value = '';
            currentReemplazoForm = null;
        }

        // Configurar botón de confirmación del modal de reemplazo
        document.getElementById('confirmarReemplazoBtn').addEventListener('click', function() {
            if (currentReemplazoForm) {
                // Agregar el motivo al formulario si se proporcionó
                const motivo = document.getElementById('motivoReemplazo').value;
                if (motivo.trim()) {
                    const motivoInput = document.createElement('input');
                    motivoInput.type = 'hidden';
                    motivoInput.name = 'motivo';
                    motivoInput.value = motivo.trim();
                    currentReemplazoForm.appendChild(motivoInput);
                }
                
                // Mostrar loading
                const confirmBtn = document.getElementById('confirmarReemplazoBtn');
                const originalText = confirmBtn.innerHTML;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                confirmBtn.disabled = true;

                // Enviar el formulario
                currentReemplazoForm.submit();
            }
        });

        // Cerrar modal al hacer click fuera
        document.getElementById('reemplazoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Funciones auxiliares
        function confirmarTodas() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function verCalendario() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function exportarReporte() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function historialAsistencia() {
            mostrarMensaje('Funcionalidad en desarrollo', 'info');
        }

        function solicitarAsignacion() {
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