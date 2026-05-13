<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Asistencia y Reemplazo</h2>
                    <p class="text-blue-100 text-sm">Confirma tu asistencia o solicita reemplazo</p>
                </div>
                <div class="flex space-x-3">
                    <button id="filterBtn" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                    <button id="refreshBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-sync-alt mr-2"></i> Actualizar
                    </button>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Errores encontrados:</strong>
                    </div>
                    <ul class="list-disc list-inside ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Panel de estadísticas -->
            @php
                $totalProgramaciones = $programaciones->count();
                $confirmadas = $programaciones->where('estado', 'Confirmado')->count();
                $pendientes = $programaciones->where('estado', 'Pendiente')->count();
                $reemplazadas = $programaciones->where('estado', 'Reemplazado')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-xs uppercase tracking-wider">Total</p>
                            <p class="text-white text-2xl font-bold">{{ $totalProgramaciones }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-pie text-white"></i>
                        </div>
                    </div>
                </div>
               <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4">
                        <div>
                            <p class="text-green-100 text-xs uppercase tracking-wider">Confirmadas</p>
                            <p class="text-white text-2xl font-bold">{{ $confirmadas }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4">
                        <div>
                            <p class="text-yellow-100 text-xs uppercase tracking-wider">Pendientes</p>
                            <p class="text-white text-2xl font-bold">{{ $pendientes }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4">
                        <div>
                            <p class="text-red-100 text-xs uppercase tracking-wider">Reemplazos</p>
                            <p class="text-white text-2xl font-bold">{{ $reemplazadas }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del servidor y acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-purple-600 to-pink-700 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-xs uppercase tracking-wider">Servidor</p>
                            <p class="text-white font-bold">{{ session('usuario_api.nombre', 'Usuario') }}</p>
                            <p class="text-purple-100 text-xs">{{ $programaciones->groupBy('id_asignacion')->count() }} roles asignados</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 rounded-xl shadow-lg p-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-bolt text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-teal-100 text-xs uppercase tracking-wider">Acciones Rápidas</p>
                                <p class="text-white text-sm">Gestión rápida</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="confirmarTodas()" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                <i class="fas fa-check-double mr-1"></i> Confirmar Todo
                            </button>
                            <button onclick="verCalendario()" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                <i class="fas fa-calendar-alt mr-1"></i> Calendario
                            </button>
                            <button onclick="exportarReporte()" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                <i class="fas fa-file-export mr-1"></i> Exportar
                            </button>
                            <button onclick="historialAsistencia()" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                <i class="fas fa-history mr-1"></i> Historial
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-list-alt mr-1"></i>
                    Mostrando <span class="font-semibold">{{ $programaciones->count() }}</span> programaciones
                </div>
            </div>

            <!-- Tabla de programaciones -->
            <div class="overflow-x-auto">
                @if($programaciones->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No tienes actividades programadas</p>
                        <p class="text-gray-400 mt-2">No hay programaciones disponibles para ti</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-tasks mr-1 text-blue-500"></i> ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1 text-purple-500"></i> Actividad
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-tag mr-1 text-red-500"></i> Asignación
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar-day mr-1 text-green-500"></i> Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-check-circle mr-1 text-green-500"></i> Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($programaciones as $programacion)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $programacion->id_programacion }}</div>
                                        <div class="text-xs text-gray-500">Prog #{{ $programacion->id_programacion }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $programacion->nombre_actividad }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $programacion->id_actividad }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $programacion->nombre_asignacion }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $programacion->id_asignacion }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($programacion->fecha)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($programacion->fecha)->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($programacion->estado === 'Confirmado')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Confirmado
                                            </span>
                                        @elseif($programacion->estado === 'Reemplazado')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-exchange-alt mr-1"></i> Reemplazado
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if($programacion->estado == 'Pendiente')
                                            <form method="POST" action="{{ route('asistencia.confirmar', $programacion->id_programacion) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition text-sm">
                                                    <i class="fas fa-check mr-1"></i> Confirmar
                                                </button>
                                            </form>
                                            <button type="button" onclick="abrirModalReemplazo({{ $programacion->id_programacion }})" 
                                                    class="bg-orange-600 text-white px-3 py-1.5 rounded-lg hover:bg-orange-700 transition text-sm">
                                                <i class="fas fa-user-exchange mr-1"></i> Reemplazo
                                            </button>
                                        @elseif($programacion->estado == 'Confirmado')
                                            <span class="text-green-600 text-sm"><i class="fas fa-check-circle mr-1"></i> Asistencia Confirmada</span>
                                        @else
                                            <span class="text-blue-600 text-sm"><i class="fas fa-exchange-alt mr-1"></i> Reemplazo Completado</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal de Reemplazo -->
    <div id="reemplazoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-user-exchange mr-2 text-orange-500"></i> Solicitar Reemplazo
                </h3>
                <button onclick="cerrarModalReemplazo()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="modalContent" class="mb-6">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                    <p class="mt-2 text-gray-600">Cargando usuarios disponibles...</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="cerrarModalReemplazo()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button id="confirmarReemplazoBtn"
                        onclick="enviarReemplazo()"
                        disabled
                        class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane mr-1"></i> Solicitar Reemplazo
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentProgramacionId = null;

        // Refresh y Filter
        document.getElementById('refreshBtn').addEventListener('click', () => location.reload());
        document.getElementById('filterBtn').addEventListener('click', () => {
            // Puedes agregar funcionalidad de filtro aquí
            mostrarMensaje('Filtros disponibles próximamente', 'info');
        });

        // Abrir modal de reemplazo
        function abrirModalReemplazo(programacionId) {
            currentProgramacionId = programacionId;
            const modal = document.getElementById('reemplazoModal');
            modal.classList.remove('hidden');
            document.getElementById('confirmarReemplazoBtn').disabled = true;
            
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                    <p class="mt-2 text-gray-600">Cargando usuarios disponibles...</p>
                </div>`;

            fetch('/asistencia/reemplazo/usuarios/' + programacionId, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.usuarios) {
                    mostrarListaUsuarios(data.usuarios, data.rol_actual);
                } else {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-3xl text-red-400"></i>
                            <p class="mt-2 text-gray-600">${data.message || 'No se encontraron usuarios disponibles'}</p>
                        </div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-3xl text-red-400"></i>
                        <p class="mt-2 text-gray-600">Error al conectar con el servidor.</p>
                    </div>`;
            });
        }

        // Mostrar lista de usuarios
        function mostrarListaUsuarios(usuarios, rolActual) {
            let html = '';
            
            if (rolActual) {
                html += `<div class="mb-3 p-2 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">Rol requerido: <strong>${rolActual}</strong></p>
                         </div>`;
            }

            if (usuarios.length === 0) {
                html += `<div class="text-center py-4 bg-orange-50 rounded-lg mb-4">
                            <i class="fas fa-users-slash text-2xl text-orange-400"></i>
                            <p class="mt-2 text-sm text-gray-600">No hay otros servidores con el mismo rol disponibles.</p>
                         </div>`;
            } else {
                html += `<label class="block text-sm font-medium text-gray-700 mb-2">Selecciona quién te reemplazará:</label>
                         <div class="max-h-48 overflow-y-auto space-y-2 mb-4 border border-gray-200 rounded-lg p-2">`;
                
                usuarios.forEach(usuario => {
                    const iniciales = (usuario.nombre_usuario || 'U').substring(0, 2).toUpperCase();
                    html += `<div class="usuario-card border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-blue-50 transition-colors" 
                                    onclick="seleccionarUsuario(this, ${usuario.id_asignacion}, '${usuario.nombre_usuario.replace(/'/g, "\\'")}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                            ${iniciales}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">${usuario.nombre_usuario}</p>
                                            <p class="text-xs text-gray-500">${usuario.nombre_rol || ''}</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-circle text-gray-200 text-xs check-icon"></i>
                                </div>
                            </div>`;
                });
                html += `</div>`;
            }

            html += `<div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo del reemplazo <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivoReemplazo" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none" 
                                  placeholder="Describe el motivo (mínimo 10 caracteres)..."></textarea>
                        <p id="motivoError" class="text-red-500 text-xs mt-1 hidden">Mínimo 10 caracteres.</p>
                    </div>
                    <input type="hidden" id="selectedAsignacionId" value="">`;

            document.getElementById('modalContent').innerHTML = html;

            if (usuarios.length === 0) {
                document.getElementById('confirmarReemplazoBtn').disabled = false;
            }

            // Agregar evento para validar motivo en tiempo real
            const motivoInput = document.getElementById('motivoReemplazo');
            if (motivoInput) {
                motivoInput.addEventListener('input', function() {
                    const errorEl = document.getElementById('motivoError');
                    if (this.value.trim().length >= 10) {
                        errorEl.classList.add('hidden');
                    } else {
                        errorEl.classList.remove('hidden');
                    }
                });
            }
        }

        // Seleccionar usuario
        function seleccionarUsuario(el, asignacionId, nombre) {
            document.querySelectorAll('.usuario-card').forEach(card => {
                card.classList.remove('bg-blue-100', 'border-blue-500');
                card.classList.add('border-gray-200');
                const icon = card.querySelector('.check-icon');
                if (icon) {
                    icon.className = 'fas fa-circle text-gray-200 text-xs check-icon';
                }
            });

            el.classList.remove('border-gray-200');
            el.classList.add('bg-blue-100', 'border-blue-500');
            const checkIcon = el.querySelector('.check-icon');
            if (checkIcon) {
                checkIcon.className = 'fas fa-check-circle text-blue-500 text-sm check-icon';
            }

            document.getElementById('selectedAsignacionId').value = asignacionId;
            
            const motivo = document.getElementById('motivoReemplazo')?.value.trim() || '';
            document.getElementById('confirmarReemplazoBtn').disabled = motivo.length < 10;
        }

        // Enviar reemplazo
        function enviarReemplazo() {
            const motivoEl = document.getElementById('motivoReemplazo');
            const errorEl = document.getElementById('motivoError');
            const asignacionId = document.getElementById('selectedAsignacionId')?.value || '';

            if (!motivoEl || motivoEl.value.trim().length < 10) {
                if (errorEl) errorEl.classList.remove('hidden');
                if (motivoEl) motivoEl.focus();
                return;
            }
            if (errorEl) errorEl.classList.add('hidden');

            const btn = document.getElementById('confirmarReemplazoBtn');
            const textoOriginal = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enviando...';

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("asistencia.reemplazo") }}';

            const inputs = [
                { name: '_token', value: '{{ csrf_token() }}' },
                { name: 'id_programacion', value: currentProgramacionId },
                { name: 'motivo', value: motivoEl.value.trim() },
                { name: 'id_asignacion_reemplazo', value: asignacionId }
            ];

            inputs.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = field.name;
                input.value = field.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Cerrar modal
        function cerrarModalReemplazo() {
            const modal = document.getElementById('reemplazoModal');
            modal.classList.add('hidden');
            currentProgramacionId = null;
        }

        // Cerrar al hacer clic fuera
        document.getElementById('reemplazoModal').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalReemplazo();
        });

        // Funciones placeholder
        function confirmarTodas() { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
        function verCalendario() { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
        function exportarReporte() { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
        function historialAsistencia() { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }

        // Mostrar mensajes flotantes
        function mostrarMensaje(mensaje, tipo) {
            let container = document.getElementById('mensajeContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'mensajeContainer';
                container.className = 'fixed top-4 right-4 z-50 space-y-2';
                document.body.appendChild(container);
            }

            const colores = {
                success: 'bg-green-100 border-l-4 border-green-500 text-green-700',
                error: 'bg-red-100 border-l-4 border-red-500 text-red-700',
                info: 'bg-blue-100 border-l-4 border-blue-500 text-blue-700',
            };

            const iconos = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
            };

            const div = document.createElement('div');
            div.className = `p-4 rounded shadow-lg ${colores[tipo] || colores.info} max-w-sm`;
            div.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${iconos[tipo] || iconos.info} mr-3"></i>
                    <span class="font-medium">${mensaje}</span>
                    <button onclick="this.closest('.max-w-sm').remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.appendChild(div);

            setTimeout(() => {
                if (div.parentElement) div.remove();
            }, 5000);
        }
    </script>
</main>
</x-app-layout>