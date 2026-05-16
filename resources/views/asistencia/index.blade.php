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
                $confirmadas = $programaciones->where('estado', 'Confirmado')->count();
                $pendientes = $programaciones->where('estado', 'Pendiente')->count();
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 bg-gray-50 border-b border-gray-200">

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

                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 rounded-xl shadow-lg p-4">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="confirmarTodas()" class="bg-white/20 text-white px-3 py-2 rounded-lg text-sm hover:bg-white/30">
                            Confirmar Todo
                        </button>
                        <button onclick="verCalendario()" class="bg-white/20 text-white px-3 py-2 rounded-lg text-sm hover:bg-white/30">
                            Calendario
                        </button>
                        <button onclick="exportarReporte()" class="bg-white/20 text-white px-3 py-2 rounded-lg text-sm hover:bg-white/30">
                            Exportar
                        </button>
                        <button onclick="historialAsistencia()" class="bg-white/20 text-white px-3 py-2 rounded-lg text-sm hover:bg-white/30">
                            Historial
                        </button>
                    </div>
                </div>

            </div>

            <!-- Tabla -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando <span class="font-bold">{{ $programaciones->count() }}</span> programaciones
                </p>
            </div>

            <div class="overflow-x-auto">
                @if($programaciones->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No tienes actividades programadas</p>
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
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Confirmado
                                            </span>
                                        @elseif($programacion->estado === 'Reemplazado')
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Reemplazado
                                            </span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($programacion->estado == 'Pendiente')
                                            <form method="POST" action="{{ route('asistencia.confirmar', $programacion->id_programacion) }}" class="inline">
                                                @csrf
                                                <button class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                                                    Confirmar
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="abrirModalReemplazo({{ $programacion->id_programacion }})"
                                                class="bg-orange-500 text-white px-3 py-1 rounded-lg hover:bg-orange-600 ml-2">
                                                Reemplazo
                                            </button>
                                        @else
                                            <span class="text-gray-500 text-sm">Sin acciones</span>
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

    <!-- Tu modal y script déjalos igual -->
</x-app-layout>