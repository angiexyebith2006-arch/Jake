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
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Asistencia y Reemplazo</h1>
                <p class="text-gray-600">Confirma tu asistencia o solicita reemplazo</p>
            </div>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <button id="filterBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-50">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
                <button id="refreshBtn" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
            </div>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-700"><i class="fas fa-times"></i></button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-700"><i class="fas fa-times"></i></button>
            </div>
        </div>
        @endif
        @if(session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500 text-xl"></i>
                <span class="font-medium">{{ session('info') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-blue-700"><i class="fas fa-times"></i></button>
            </div>
        </div>
        @endif

        <!-- Estadísticas -->
        @php
            $totalProgramaciones = $programaciones->count();
            $confirmadas  = $programaciones->where('estado', 'Confirmado')->count();
            $pendientes   = $programaciones->where('estado', 'Pendiente')->count();
            $reemplazadas = $programaciones->where('estado', 'Reemplazado')->count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl rounded-2xl overflow-hidden">
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
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/10 p-3 rounded-lg"><p class="text-blue-100 text-xs">Total</p><p class="text-white text-xl font-bold">{{ $totalProgramaciones }}</p></div>
                        <div class="bg-white/10 p-3 rounded-lg"><p class="text-blue-100 text-xs">Confirmadas</p><p class="text-white text-xl font-bold">{{ $confirmadas }}</p></div>
                        <div class="bg-white/10 p-3 rounded-lg"><p class="text-blue-100 text-xs">Pendientes</p><p class="text-white text-xl font-bold">{{ $pendientes }}</p></div>
                        <div class="bg-white/10 p-3 rounded-lg"><p class="text-blue-100 text-xs">Reemplazos</p><p class="text-white text-xl font-bold">{{ $reemplazadas }}</p></div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-600 to-pink-700 shadow-xl rounded-2xl overflow-hidden">
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
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(session('usuario_api.nombre', 'U'), 0, 2) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-white font-medium text-sm">{{ session('usuario_api.nombre', 'Usuario') }}</p>
                                <p class="text-purple-100 text-xs">{{ $programaciones->groupBy('id_asignacion')->count() }} roles asignados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 bg-gradient-to-r from-green-600 to-teal-700 shadow-xl rounded-2xl overflow-hidden">
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
                        <button onclick="confirmarTodas()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl text-sm"><i class="fas fa-check-double mr-2"></i>Confirmar Todo</button>
                        <button onclick="verCalendario()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl text-sm"><i class="fas fa-calendar-alt mr-2"></i>Calendario</button>
                        <button onclick="exportarReporte()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl text-sm"><i class="fas fa-file-export mr-2"></i>Exportar</button>
                        <button onclick="historialAsistencia()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-xl text-sm"><i class="fas fa-history mr-2"></i>Historial</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Mis Programaciones</h2>
                <p class="text-blue-100 text-sm">Gestiona tu asistencia a las actividades programadas</p>
            </div>
            <div class="p-6">
                @if($programaciones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-tasks mr-2 text-blue-500"></i>ID</th>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Actividad</th>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-user-tag mr-2 text-red-500"></i>Asignación</th>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-calendar-day mr-2 text-green-500"></i>Fecha</th>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-check-circle mr-2 text-green-500"></i>Estado</th>
                                <th class="p-4 font-semibold text-gray-700 border-b"><i class="fas fa-cogs mr-2 text-orange-500"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programaciones as $programacion)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors">
                                <td class="p-4">
                                    <span class="text-gray-700 font-medium">{{ $programacion->id_programacion }}</span><br>
                                    <span class="text-xs text-gray-500">Prog #{{ $programacion->id_programacion }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="text-gray-700 font-medium">{{ $programacion->nombre_actividad }}</span><br>
                                    <span class="text-xs text-gray-500">ID: {{ $programacion->id_actividad }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="text-gray-700 font-medium">{{ $programacion->nombre_asignacion }}</span><br>
                                    <span class="text-xs text-gray-500">ID: {{ $programacion->id_asignacion }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($programacion->fecha)->format('d/m/Y') }}</span><br>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($programacion->fecha)->diffForHumans() }}</span>
                                </td>
                                <td class="p-4">
                                    @if($programacion->estado === 'Confirmado')
                                        <span class="inline-flex items-center bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-semibold"><i class="fas fa-check-circle mr-1"></i>Confirmado</span>
                                    @elseif($programacion->estado === 'Reemplazado')
                                        <span class="inline-flex items-center bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-xs font-semibold"><i class="fas fa-exchange-alt mr-1"></i>Reemplazado</span>
                                    @else
                                        <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full text-xs font-semibold"><i class="fas fa-clock mr-1"></i>Pendiente</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($programacion->estado == 'Pendiente')
    <div class="flex space-x-2">
        <form method="POST" action="{{ route('asistencia.confirmar', $programacion->id_programacion) }}" class="inline">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600...">
                <i class="fas fa-check mr-1"></i>Confirmar
            </button>
        </form>
        <button type="button" onclick="abrirModalReemplazo({{ $programacion->id_programacion }})"...>
            <i class="fas fa-user-exchange mr-1"></i>Reemplazo
        </button>
    </div>
@elseif($programacion->estado == 'Confirmado')
    <span class="bg-green-100...">Asistencia Confirmada</span>
@else
    <span class="bg-red-100...">Reemplazo Completado</span>
@endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sin programaciones</h3>
                    <p class="text-gray-500">No tienes actividades programadas.</p>
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal — siempre presente en el DOM, oculto con style -->
    <div id="reemplazoModal"
         style="display:none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-user-exchange mr-2 text-orange-500"></i>Solicitar Reemplazo
                </h3>
                <button onclick="cerrarModalReemplazo()" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Zona de contenido dinámico -->
            <div id="modalContent" class="mb-6"></div>

            <div class="flex justify-end space-x-3">
                <button onclick="cerrarModalReemplazo()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50">
                    Cancelar
                </button>
                <button id="confirmarReemplazoBtn"
                        onclick="enviarReemplazo()"
                        disabled
                        class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl hover:from-orange-600 hover:to-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane mr-1"></i>Solicitar Reemplazo
                </button>
            </div>
        </div>
    </div>

    <script>
    var currentProgramacionId = null;

    document.getElementById('refreshBtn').addEventListener('click', function() { location.reload(); });
    document.getElementById('filterBtn').addEventListener('click', function() {
        var s = document.getElementById('filterSection');
        if (s) s.classList.toggle('hidden');
    });

    // ── Abrir modal ─────────────────────────────────────
    function abrirModalReemplazo(programacionId) {
        currentProgramacionId = programacionId;

        document.getElementById('reemplazoModal').style.display = 'flex';
        document.getElementById('confirmarReemplazoBtn').disabled = true;
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                <p class="mt-2 text-gray-600">Cargando usuarios disponibles...</p>
            </div>`;

        fetch('/asistencia/reemplazo/usuarios/' + programacionId, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success && data.usuarios) {
                mostrarListaUsuarios(data.usuarios, data.rol_actual);
            } else {
                document.getElementById('modalContent').innerHTML =
                    '<div class="text-center py-8">' +
                    '<i class="fas fa-exclamation-circle text-3xl text-red-400"></i>' +
                    '<p class="mt-2 text-gray-600">' + (data.message || 'No se encontraron usuarios disponibles') + '</p>' +
                    '</div>';
            }
        })
        .catch(function(err) {
            console.error('Error fetch:', err);
            document.getElementById('modalContent').innerHTML =
                '<div class="text-center py-8">' +
                '<i class="fas fa-exclamation-circle text-3xl text-red-400"></i>' +
                '<p class="mt-2 text-gray-600">Error al conectar con el servidor.</p>' +
                '</div>';
        });
    }

    // ── Mostrar lista de usuarios + textarea ─────────────
    function mostrarListaUsuarios(usuarios, rolActual) {
        var rolTexto = rolActual ? 'Rol requerido: <strong>' + rolActual + '</strong>' : '';

        var html = '<div class="mb-3">' +
            '<p class="text-gray-600 text-sm mb-1">Selecciona quién te reemplazará:</p>' +
            '<p class="text-sm text-blue-600 mb-3">' + rolTexto + '</p>' +
            '</div>';

        if (usuarios.length === 0) {
            html += '<div class="text-center py-4 bg-orange-50 rounded-xl mb-4">' +
                '<i class="fas fa-users-slash text-2xl text-orange-400"></i>' +
                '<p class="mt-2 text-sm text-gray-600">No hay otros servidores con el mismo rol disponibles.</p>' +
                '</div>';
        } else {
            html += '<div class="max-h-48 overflow-y-auto space-y-2 mb-4">';
            usuarios.forEach(function(usuario) {
                var iniciales = (usuario.nombre_usuario || 'U').substring(0, 2).toUpperCase();
                html += '<div class="usuario-card border border-gray-200 rounded-xl p-3 cursor-pointer hover:bg-blue-50 transition-colors" ' +
                    'onclick="seleccionarUsuario(this, ' + usuario.id_asignacion + ', \'' + usuario.nombre_usuario.replace(/'/g, "\\'") + '\')">' +
                    '<div class="flex items-center justify-between">' +
                    '<div class="flex items-center">' +
                    '<div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">' + iniciales + '</div>' +
                    '<div>' +
                    '<p class="font-medium text-gray-800 text-sm">' + usuario.nombre_usuario + '</p>' +
                    '<p class="text-xs text-gray-500">' + (usuario.nombre_rol || '') + '</p>' +
                    '</div></div>' +
                    '<i class="fas fa-circle text-gray-200 text-xs check-icon"></i>' +
                    '</div></div>';
            });
            html += '</div>';
        }

        html += '<div>' +
            '<label class="block text-sm font-semibold text-gray-700 mb-2">Motivo del reemplazo <span class="text-red-500">*</span></label>' +
            '<textarea id="motivoReemplazo" rows="3" ' +
            'class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none" ' +
            'placeholder="Describe el motivo (mínimo 10 caracteres)..."></textarea>' +
            '<p id="motivoError" class="text-red-500 text-xs mt-1" style="display:none;">Mínimo 10 caracteres.</p>' +
            '</div>' +
            '<input type="hidden" id="selectedAsignacionId" value="">';

        document.getElementById('modalContent').innerHTML = html;

        // Si no hay usuarios, habilitar el botón igual (solo con motivo)
        if (usuarios.length === 0) {
            document.getElementById('confirmarReemplazoBtn').disabled = false;
        }
    }

    // ── Seleccionar usuario de la lista ─────────────────
    function seleccionarUsuario(el, asignacionId, nombre) {
        // Limpiar selección anterior
        document.querySelectorAll('.usuario-card').forEach(function(card) {
            card.classList.remove('bg-blue-100', 'border-blue-500');
            card.classList.add('border-gray-200');
            card.querySelector('.check-icon').className = 'fas fa-circle text-gray-200 text-xs check-icon';
        });

        // Marcar seleccionado
        el.classList.remove('border-gray-200');
        el.classList.add('bg-blue-100', 'border-blue-500');
        el.querySelector('.check-icon').className = 'fas fa-check-circle text-blue-500 text-sm check-icon';

        document.getElementById('selectedAsignacionId').value = asignacionId;
        document.getElementById('confirmarReemplazoBtn').disabled = false;
    }

    // ── Enviar solicitud ────────────────────────────────
    function enviarReemplazo() {
        var motivoEl     = document.getElementById('motivoReemplazo');
        var errorEl      = document.getElementById('motivoError');
        var asignacionId = document.getElementById('selectedAsignacionId')?.value || '';

        if (!motivoEl || motivoEl.value.trim().length < 10) {
            if (errorEl) errorEl.style.display = 'block';
            if (motivoEl) motivoEl.focus();
            return;
        }
        if (errorEl) errorEl.style.display = 'none';

        var btn = document.getElementById('confirmarReemplazoBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Enviando...';

        var form = document.createElement('form');
        form.method = 'POST';
        form.action  = '{{ route("asistencia.reemplazo") }}';

        [
            { name: '_token',          value: '{{ csrf_token() }}' },
            { name: 'id_programacion', value: currentProgramacionId },
            { name: 'motivo',          value: motivoEl.value.trim() },
            { name: 'id_asignacion_reemplazo', value: asignacionId }
        ].forEach(function(f) {
            var input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = f.name;
            input.value = f.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // ── Cerrar modal ────────────────────────────────────
    function cerrarModalReemplazo() {
        document.getElementById('reemplazoModal').style.display = 'none';
        currentProgramacionId = null;
    }

    document.getElementById('reemplazoModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalReemplazo();
    });

    // El botón llama enviarReemplazo() via onclick en el HTML
    document.getElementById('confirmarReemplazoBtn').onclick = enviarReemplazo;

    function confirmarTodas()     { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
    function verCalendario()      { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
    function exportarReporte()    { mostrarMensaje('Funcionalidad en desarrollo', 'info'); }
    function historialAsistencia(){ mostrarMensaje('Funcionalidad en desarrollo', 'info'); }

    function mostrarMensaje(mensaje, tipo) {
        var c = document.createElement('div');
        c.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ' +
            (tipo === 'success' ? 'bg-green-100 text-green-800 border border-green-300' :
             tipo === 'error'   ? 'bg-red-100 text-red-800 border border-red-300' :
                                  'bg-blue-100 text-blue-800 border border-blue-300');
        c.innerHTML = '<div class="flex items-center">' +
            '<span class="font-medium">' + mensaje + '</span>' +
            '<button onclick="this.parentElement.parentElement.remove()" class="ml-4"><i class="fas fa-times"></i></button>' +
            '</div>';
        document.body.appendChild(c);
        setTimeout(function() { c.remove(); }, 5000);
    }
</script>
</body>
</html>
</x-app-layout>