<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- BUG 4 FIX: meta csrf-token en el <head> para que fetch() lo encuentre --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autorizaciones Pendientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <main class="p-6 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-clipboard-check mr-2 text-blue-500"></i>
                    Autorizaciones Pendientes
                </h1>
                <p class="text-gray-600">Gestiona las solicitudes de reemplazo pendientes de aprobación</p>
            </div>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <button id="refreshBtn"
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
            </div>
        </div>

        <!-- Panel estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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
                            <p class="text-white text-xl font-bold">
                                {{ $autorizaciones->where('estado', 'Pendiente')->count() }}
                            </p>
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
        </div>

        <!-- Tabla principal -->
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
                    @if($autorizaciones->count() > 0)
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
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">

                                        {{-- Programación --}}
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-700 font-medium">
                                                    Programación #{{ $autorizacion['id_programacion'] ?? 'N/A' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ isset($autorizacion['fecha_autorizacion'])
                                                        ? \Carbon\Carbon::parse($autorizacion['fecha_autorizacion'])->format('d/m/Y')
                                                        : 'Fecha no disponible' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Servidor Original --}}
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ strtoupper(substr($autorizacion['servidor_original_nombre'] ?? 'U', 0, 2)) }}
                                                </div>
                                                <span class="text-gray-700 text-sm">
                                                    {{ $autorizacion['servidor_original_nombre'] ?? 'No disponible' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Servidor Reemplazo --}}
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ strtoupper(substr($autorizacion['servidor_reemplazo_nombre'] ?? 'U', 0, 2)) }}
                                                </div>
                                                <span class="text-gray-700 text-sm">
                                                    {{ $autorizacion['servidor_reemplazo_nombre'] ?? 'No sugerido' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Motivo --}}
                                        <td class="p-4">
                                            <span class="text-gray-700 text-sm">
                                                {{ $autorizacion['motivo'] ?? 'Sin motivo' }}
                                            </span>
                                        </td>

                                        {{-- Autorizador --}}
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    {{ strtoupper(substr($autorizacion['autorizador_nombre'] ?? 'A', 0, 2)) }}
                                                </div>
                                                <span class="text-gray-700 text-sm font-medium">
                                                    {{ $autorizacion['autorizador_nombre'] ?? 'No asignado' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <button onclick="abrirModal({{ $autorizacion['id'] }}, 'aprobar')"
                                                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-medium">
                                                    <i class="fas fa-check mr-1"></i>Aprobar
                                                </button>
                                                <button onclick="abrirModal({{ $autorizacion['id'] }}, 'rechazar')"
                                                        class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-4 py-2 rounded-xl text-sm font-medium">
                                                    <i class="fas fa-times mr-1"></i>Rechazar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
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
                                <a href="{{ route('asistencia.index') }}"
                                   class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
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
    <div id="observacionModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800"></h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                <p id="modalDescription" class="text-gray-600 mb-4"></p>
                <textarea id="observacionesInput"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="4"
                          placeholder="Ingresa observaciones..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="cerrarModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                {{-- BUG 3 FIX: un solo botón sin listener duplicado --}}
                <button id="modalConfirmBtn"
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <script>
    // ─── Estado global ────────────────────────────────────────────────────────
    let currentAutorizacionId = null;
    let currentAction         = '';

    // BUG 4 FIX: leer CSRF desde el meta-tag del head (estándar Laravel)
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // ─── Abrir modal ─────────────────────────────────────────────────────────
    // BUG 3 FIX: una sola función unificada reemplaza aprobarAutorizacion y
    // rechazarAutorizacion, eliminando la duplicación de event listeners.
    function abrirModal(id, accion) {
        currentAutorizacionId = id;
        currentAction         = accion;

        if (accion === 'aprobar') {
            document.getElementById('modalTitle').textContent       = 'Aprobar Autorización';
            document.getElementById('modalDescription').textContent = '¿Estás seguro de aprobar esta solicitud de reemplazo?';
            document.getElementById('observacionesInput').placeholder = 'Observaciones (opcional)...';
        } else {
            document.getElementById('modalTitle').textContent       = 'Rechazar Autorización';
            document.getElementById('modalDescription').textContent = '¿Estás seguro de rechazar esta solicitud? Debes incluir el motivo.';
            document.getElementById('observacionesInput').placeholder = 'Motivo del rechazo (requerido)...';
        }

        document.getElementById('observacionesInput').value = '';
        document.getElementById('observacionModal').classList.remove('hidden');
    }

    function cerrarModal() {
        document.getElementById('observacionModal').classList.add('hidden');
        document.getElementById('observacionesInput').value = '';
        currentAutorizacionId = null;
        currentAction         = '';
    }

    // ─── Confirmar acción (UN SOLO listener) ─────────────────────────────────
    document.getElementById('modalConfirmBtn').addEventListener('click', async function () {
        const observaciones = document.getElementById('observacionesInput').value.trim();

        if (currentAction === 'rechazar' && !observaciones) {
            mostrarMensaje('Debes ingresar un motivo para rechazar.', 'error');
            return;
        }

        const url = currentAction === 'aprobar'
            ? `/autorizaciones/${currentAutorizacionId}/aprobar`
            : `/autorizaciones/${currentAutorizacionId}/rechazar`;

        const btn          = document.getElementById('modalConfirmBtn');
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        btn.disabled  = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // BUG 4 FIX: CSRF leído del meta-tag
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept':        'application/json',
                },
                body: JSON.stringify({ observaciones }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                mostrarMensaje(data.message, 'success');
                cerrarModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarMensaje('Error: ' + (data.message ?? 'Error desconocido'), 'error');
                btn.innerHTML = textoOriginal;
                btn.disabled  = false;
            }
        } catch (error) {
            console.error('Error fetch:', error);
            mostrarMensaje('Error de red al procesar la solicitud.', 'error');
            btn.innerHTML = textoOriginal;
            btn.disabled  = false;
        }
    });

    // ─── Cerrar al hacer clic fuera ───────────────────────────────────────────
    document.getElementById('observacionModal').addEventListener('click', function (e) {
        if (e.target === this) cerrarModal();
    });

    // ─── Refresh ─────────────────────────────────────────────────────────────
    document.getElementById('refreshBtn').addEventListener('click', () => location.reload());

    // ─── Mensajes flash de Laravel ────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            mostrarMensaje('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            mostrarMensaje('{{ session('error') }}', 'error');
        @endif
    });

    // ─── Helper notificaciones ───────────────────────────────────────────────
    function mostrarMensaje(mensaje, tipo) {
        let container = document.getElementById('mensajeContainer');
        if (!container) {
            container = document.createElement('div');
            container.id        = 'mensajeContainer';
            container.className = 'fixed top-4 right-4 z-50';
            document.body.appendChild(container);
        }

        const colores = {
            success: 'bg-green-100 text-green-800 border border-green-300',
            error:   'bg-red-100   text-red-800   border border-red-300',
            info:    'bg-blue-100  text-blue-800  border border-blue-300',
        };
        const iconos = {
            success: 'fa-check-circle',
            error:   'fa-exclamation-circle',
            info:    'fa-info-circle',
        };

        const div = document.createElement('div');
        div.className = `p-4 mb-4 rounded-lg shadow-lg ${colores[tipo] ?? colores.info}`;
        div.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${iconos[tipo] ?? iconos.info} mr-3"></i>
                <span class="font-medium">${mensaje}</span>
                <button onclick="this.closest('.mb-4').remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        container.appendChild(div);

        setTimeout(() => { if (div.parentElement) div.remove(); }, 5000);
    }
    </script>
</body>
</html>
</x-app-layout>