<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">

        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
                <h2 class="text-white text-2xl font-bold">Gestión de Permisos</h2>
                <p class="text-green-100 text-sm mt-1">Asigna permisos por vista y acción</p>
            </div>

            <form method="POST" action="{{ route('permisos.storeMultiple') }}">
                @csrf

                <!-- ASIGNACIÓN - MEJORADA -->
                <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2 text-green-600"></i>
                        Seleccionar Asignación
                    </label>
                    <div class="relative">
                        <select name="asignacion_id"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white py-3 px-4 text-gray-700 appearance-none cursor-pointer transition-all duration-200 hover:border-green-400">
                            <option value="" class="text-gray-400">Seleccione una asignación</option>
                            @foreach($asignaciones ?? [] as $asignacion)
                                <option value="{{ $asignacion['idAsignacion'] ?? '' }}" class="py-2">
                                    #{{ $asignacion['idAsignacion'] ?? '' }} — 
                                    {{ $asignacion['usuarioNombre'] ?? 'Sin nombre' }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Icono de flecha personalizado -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-green-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    
                    @if(empty($asignaciones))
                        <div class="mt-3 flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                            <p class="text-xs">No se encontraron asignaciones disponibles.</p>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Selecciona el usuario al que deseas asignar los permisos
                        </p>
                    @endif
                </div>

                <!-- TABLA DE PERMISOS -->
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-green-600"></i>
                        Permisos por vista
                    </h3>

                    @if(empty($vistas))
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-table text-4xl mb-2"></i>
                            <p class="text-lg">No hay vistas disponibles</p>
                            <p class="text-sm mt-1">Verifica la conexión con la API</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <i class="fas fa-eye mr-1 text-green-500"></i> Vista
                                        </th>
                                        @foreach($acciones as $accion)
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                                @php
                                                    $icono = match($accion['nombre']) {
                                                        'crear' => 'fa-plus',
                                                        'editar' => 'fa-edit',
                                                        'eliminar' => 'fa-trash-alt',
                                                        'ver' => 'fa-eye',
                                                        'responder' => 'fa-reply',
                                                        default => 'fa-circle'
                                                    };
                                                    $color = match($accion['nombre']) {
                                                        'crear' => 'text-blue-500',
                                                        'editar' => 'text-amber-500',
                                                        'eliminar' => 'text-red-500',
                                                        'ver' => 'text-purple-500',
                                                        'responder' => 'text-teal-500',
                                                        default => 'text-gray-500'
                                                    };
                                                @endphp
                                                <i class="fas {{ $icono }} {{ $color }} mr-1"></i>
                                                {{ $accion['nombre'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($vistas as $vista)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <td class="px-6 py-4 font-semibold text-gray-700 capitalize">
                                                <i class="fas fa-table mr-2 text-gray-400"></i>
                                                {{ $vista['nombre'] ?? 'Sin nombre' }}
                                            </td>
                                            @foreach($acciones as $accion)
                                                <td class="px-6 py-4 text-center">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="checkbox"
                                                               name="permisos[]"
                                                               value="{{ ($vista['nombre'] ?? '') . '-' . $accion['nombre'] }}"
                                                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 focus:ring-2 transition">
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- FOOTER -->
                <div class="p-6 bg-gray-50 border-t flex items-center justify-between">
                    <a href="{{ route('permisos.index') }}"
                        class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-200 hover:scale-105 shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Permisos
                    </button>
                </div>

            </form>
        </div>
    </main>
</x-app-layout>