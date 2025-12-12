<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Programación de Actividades
        </h2>
    </x-slot>

    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
        <main class="p-6 max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Programación de Actividades</h1>
                    <p class="text-gray-600">Gestiona y organiza las actividades de cada día</p>
                </div>

                <a href="{{ route('programacion.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
                    <i class="fas fa-plus mr-2"></i> Nueva Programación
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Programaciones Hoy</p>
                            <p class="text-lg font-bold text-gray-800" id="count-hoy">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-green-100">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-xl">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Servidores Activos</p>
                            <p class="text-lg font-bold text-gray-800" id="count-servidores">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-purple-100">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-xl">
                            <i class="fas fa-hands-praying text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Ministerios Activos</p>
                            <p class="text-lg font-bold text-gray-800" id="count-ministerios">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-orange-100">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-3 rounded-xl">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Pendientes</p>
                            <p class="text-lg font-bold text-gray-800" id="count-pendientes">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-6 rounded-2xl shadow-lg mb-6 border border-gray-200">
                <form id="filtrosForm" method="GET" action="{{ route('programacion.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-2">
                                <option value="">Todos los estados</option>
                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Confirmado" {{ request('estado') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="Reemplazado" {{ request('estado') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="fecha" value="{{ request('fecha') }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2">
                        </div>

                        <div class="flex items-end space-x-3">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl">
                                Aplicar Filtros
                            </button>
                            <a href="{{ route('programacion.index') }}"
                                class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-center">
                                Limpiar
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Tabla -->
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Programaciones</h2>
                    <p class="text-blue-100 text-sm">Lista de programaciones</p>
                </div>

                <div class="p-6">

                    {{-- Mensajes --}}
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th class="p-4 font-semibold text-gray-700">Fecha</th>
                                    <th class="p-4 font-semibold text-gray-700">Hora Inicio</th>
                                    <th class="p-4 font-semibold text-gray-700">Hora Fin</th>
                                    <th class="p-4 font-semibold text-gray-700">Actividad</th>
                                    <th class="p-4 font-semibold text-gray-700">Ministerio</th>
                                    <th class="p-4 font-semibold text-gray-700">Servidor</th>
                                    <th class="p-4 font-semibold text-gray-700">Rol</th>
                                    <th class="p-4 font-semibold text-gray-700">Estado</th>
                                    <th class="p-4 font-semibold text-gray-700">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tabla-programaciones">
                                @forelse ($programaciones as $p)
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition programacion-fila"
                                        id="fila-{{ $p->id_programacion }}">

                                        <td class="p-4">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                                        <td class="p-4">{{ $p->horaInicio }}</td>
                                        <td class="p-4">{{ $p->horaFin }}</td>
                                        <td class="p-4">{{ $p->actividad->nombre_actividad }}</td>
                                        <td class="p-4">{{ $p->actividad->ministerio->nombreMinisterio }}</td>
                                        <td class="p-4">{{ $p->asignacion->usuario->nombre }}</td>
                                        <td class="p-4">{{ $p->asignacion->rol->nombre_rol }}</td>

                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $p->estado=='Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($p->estado=='Confirmado' ? 'bg-green-100 text-green-800' :
                                                   'bg-red-100 text-red-800') }}">
                                                {{ $p->estado }}
                                            </span>
                                        </td>

                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('programacion.edit',$p->id_programacion) }}"
                                                    class="px-2 py-2 bg-blue-600 text-white rounded-xl text-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button onclick="mostrarModalEliminar({{ $p->id_programacion }}, '{{ $p->actividad->nombre_actividad }}')"
                                                        class="px-2 py-2 bg-red-600 text-white rounded-xl text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="p-4 text-center text-gray-500">
                                            No hay programaciones para mostrar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-gray-600 text-sm">
                        Mostrando <strong>{{ $programaciones->count() }}</strong> programaciones
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- Modal + Script -->
    @include('programacion.partials.modal-eliminar')

</x-app-layout>