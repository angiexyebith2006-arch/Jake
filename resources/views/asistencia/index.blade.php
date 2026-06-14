<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center flex-wrap gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-white">Asistencia y Reemplazo</h2>
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

            <!-- Filtros (inicialmente ocultos) -->
            <div id="filterPanel" class="hidden bg-gray-50 border-b border-gray-200 p-6">
                <form method="GET" action="{{ route('asistencia.index') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <option value="">Todos</option>
                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Confirmado" {{ request('estado') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="Reemplazado" {{ request('estado') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha desde</label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha hasta</label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actividad</label>
                            <input type="text" name="actividad" value="{{ request('actividad') }}"
                                placeholder="Buscar por actividad..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <a href="{{ route('asistencia.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-eraser mr-2"></i> Limpiar filtros
                        </a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-search mr-2"></i> Aplicar filtros
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $totalProgramaciones = $programaciones->count();
                $confirmadas  = $programaciones->where('estado', 'Confirmado')->count();
                $pendientes   = $programaciones->where('estado', 'Pendiente')->count();
                $reemplazadas = $programaciones->where('estado', 'Reemplazado')->count();
            @endphp

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-blue-100 text-xs uppercase">Total</p>
                            <p class="text-white text-2xl font-bold">{{ $totalProgramaciones }}</p>
                        </div>
                        <i class="fas fa-chart-pie text-white text-2xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-green-100 text-xs uppercase">Confirmadas</p>
                            <p class="text-white text-2xl font-bold">{{ $confirmadas }}</p>
                        </div>
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-yellow-100 text-xs uppercase">Pendientes</p>
                            <p class="text-white text-2xl font-bold">{{ $pendientes }}</p>
                        </div>
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-purple-100 text-xs uppercase">Reemplazos</p>
                            <p class="text-white text-2xl font-bold">{{ $reemplazadas }}</p>
                        </div>
                        <i class="fas fa-exchange-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Información usuario -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-gradient-to-r from-purple-600 to-pink-700 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-white text-3xl mr-4"></i>
                        <div>
                            <p class="text-purple-100 text-xs uppercase">Servidor</p>
                            <p class="text-white font-bold">{{ session('usuario_api.nombre', 'Usuario') }}</p>
                            <p class="text-purple-100 text-xs">{{ $programaciones->groupBy('id_asignacion')->count() }} roles asignados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contador tabla -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando <span class="font-bold">{{ $programaciones->count() }}</span> programaciones
                </p>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                @if($programaciones->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay actividades que coincidan con los filtros</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Actividad</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Asignación</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($programaciones as $programacion)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4">{{ $programacion->id_programacion }}</td>
                                    <td class="px-6 py-4">{{ $programacion->nombre_actividad }}</td>
                                    <td class="px-6 py-4">{{ $programacion->nombre_asignacion }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($programacion->fecha)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($programacion->estado === 'Confirmado')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold inline-block">Confirmado</span>
                                        @elseif($programacion->estado === 'Reemplazado')
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold inline-block">Reemplazado</span>
                                        @elseif($programacion->estado === 'Reemplazo_solicitado')
                                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold inline-block">Reemplazo Solicitado</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold inline-block">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2 flex-wrap">
                                            @if($programacion->estado === 'Pendiente')
                                                <!-- Confirmar -->
                                                <form method="POST" action="{{ route('asistencia.confirmar', $programacion->id_programacion) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs font-semibold hover:bg-green-600 transition">
                                                        <i class="fas fa-check mr-1"></i> Confirmar
                                                    </button>
                                                </form>
                                                <!-- Solicitar reemplazo -->
                                                <button onclick="abrirModalReemplazo({{ $programacion->id_programacion }})"
                                                    class="bg-orange-500 text-white px-3 py-1 rounded-lg text-xs font-semibold hover:bg-orange-600 transition">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Reemplazo
                                                </button>
                                            @elseif($programacion->estado === 'Confirmado')
                                                <span class="text-green-600 text-xs font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i> Confirmado
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">Sin acciones</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- ===================== CALENDARIO ===================== -->
            <div class="p-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Vista Calendario
                </h3>
                <div id="calendarioAsistencia"></div>
            </div>

        </div>
    </main>

    <!-- Modal Reemplazo -->
    <div id="modalReemplazo" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-exchange-alt mr-2 text-orange-500"></i> Solicitar Reemplazo
            </h3>
            <form method="POST" action="{{ route('asistencia.reemplazo.solicitar') }}" id="formReemplazo">
                @csrf
                <input type="hidden" name="id_programacion" id="reemplazo_id_programacion">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo</label>
                    <textarea name="motivo" rows="3" required minlength="10" maxlength="500"
                        placeholder="Explica el motivo del reemplazo..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reemplazante</label>
                    <select name="id_asignacion_reemplazo" id="selectReemplazante" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="">Cargando usuarios...</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalReemplazo()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        Enviar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LIBRERÍAS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fc-button {
            background-color: #4f46e5 !important;
            border: none !important;
        }
        .fc-button:hover {
            background-color: #3730a3 !important;
        }
        .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 4px !important;
            font-size: 12px !important;
        }
        .fc-daygrid-event {
            white-space: normal !important;
        }
        .fc-day-today {
            background: #eff6ff !important;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ---- Botón filtrar ----
        const filterBtn   = document.getElementById('filterBtn');
        const filterPanel = document.getElementById('filterPanel');
        if (filterBtn) {
            filterBtn.addEventListener('click', function () {
                filterPanel.classList.toggle('hidden');
            });
        }

        // ---- Botón actualizar ----
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function () {
                location.reload();
            });
        }

        // ---- CALENDARIO ----
        const calendarEl = document.getElementById('calendarioAsistencia');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                list:  'Lista'
            },
            events: [
                @foreach($programaciones as $prog)
                {
                    id: '{{ $prog->id_programacion }}',
                    title: '{{ addslashes($prog->nombre_actividad) }}',
                    start: '{{ $prog->fecha }}',
                    backgroundColor:
                        '{{ $prog->estado }}' === 'Confirmado'            ? '#16a34a' :
                        '{{ $prog->estado }}' === 'Pendiente'             ? '#eab308' :
                        '{{ $prog->estado }}' === 'Reemplazado'           ? '#2563eb' :
                        '{{ $prog->estado }}' === 'Reemplazo_solicitado'  ? '#ea580c' : '#6b7280',
                    borderColor:
                        '{{ $prog->estado }}' === 'Confirmado'            ? '#16a34a' :
                        '{{ $prog->estado }}' === 'Pendiente'             ? '#eab308' :
                        '{{ $prog->estado }}' === 'Reemplazado'           ? '#2563eb' :
                        '{{ $prog->estado }}' === 'Reemplazo_solicitado'  ? '#ea580c' : '#6b7280',
                    textColor: '#ffffff',
                    extendedProps: {
                        actividad:  '{{ addslashes($prog->nombre_actividad) }}',
                        asignacion: '{{ addslashes($prog->nombre_asignacion) }}',
                        estado:     '{{ $prog->estado }}',
                        id:         {{ $prog->id_programacion }}
                    }
                },
                @endforeach
            ],
            eventClick: function(info) {
                const e = info.event;
                const colores = {
                    'Confirmado':           '#16a34a',
                    'Pendiente':            '#eab308',
                    'Reemplazado':          '#2563eb',
                    'Reemplazo_solicitado': '#ea580c'
                };
                const color = colores[e.extendedProps.estado] || '#6b7280';
                const esPendiente = e.extendedProps.estado === 'Pendiente';

                Swal.fire({
                    title: e.extendedProps.actividad,
                    html: `
                        <div class="text-left space-y-2 text-sm">
                            <p><strong>Asignación:</strong> ${e.extendedProps.asignacion}</p>
                            <p><strong>Fecha:</strong> ${e.start.toLocaleDateString('es-CO')}</p>
                            <p><strong>Estado:</strong>
                                <span style="background:${color};color:#fff;padding:2px 10px;border-radius:20px;font-size:11px;">
                                    ${e.extendedProps.estado}
                                </span>
                            </p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    showConfirmButton: esPendiente,
                    showDenyButton: esPendiente,
                    confirmButtonText: '<i class="fas fa-check"></i> Confirmar asistencia',
                    denyButtonText: '<i class="fas fa-exchange-alt"></i> Solicitar reemplazo',
                    cancelButtonText: 'Cerrar',
                    confirmButtonColor: '#16a34a',
                    denyButtonColor: '#ea580c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/asistencia/confirmar/${e.extendedProps.id}`;
                        form.innerHTML = `@csrf`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                    if (result.isDenied) {
                        abrirModalReemplazo(e.extendedProps.id);
                    }
                });
            }
        });

        calendar.render();
    });

    // ---- Modal Reemplazo ----
    function abrirModalReemplazo(idProgramacion) {
        document.getElementById('reemplazo_id_programacion').value = idProgramacion;
        document.getElementById('selectReemplazante').innerHTML = '<option value="">Cargando...</option>';
        document.getElementById('modalReemplazo').classList.remove('hidden');

        fetch(`/asistencia/reemplazo/usuarios/${idProgramacion}`)
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('selectReemplazante');
                if (data.success && data.usuarios.length > 0) {
                    select.innerHTML = '<option value="">Selecciona un reemplazante</option>';
                    data.usuarios.forEach(u => {
                        select.innerHTML += `<option value="${u.id_asignacion}">${u.nombre_usuario} — ${u.nombre_rol}</option>`;
                    });
                } else {
                    select.innerHTML = '<option value="">No hay reemplazantes disponibles</option>';
                }
            })
            .catch(() => {
                document.getElementById('selectReemplazante').innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    function cerrarModalReemplazo() {
        document.getElementById('modalReemplazo').classList.add('hidden');
    }
    </script>

</x-app-layout>