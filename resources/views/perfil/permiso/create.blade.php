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

                <!-- ASIGNACIÓN -->
                <div class="p-6 border-b bg-gray-50">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Seleccionar Asignación
                    </label>
                    <select name="asignacion_id"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccione una asignación</option>
                        @foreach($asignaciones ?? [] as $asignacion)
                            <option value="{{ $asignacion['idAsignacion'] ?? '' }}">
                                #{{ $asignacion['idAsignacion'] ?? '' }} —
                                {{ $asignacion['usuarioNombre'] ?? 'Sin nombre' }}
                            </option>
                        @endforeach
                    </select>

                    @if(empty($asignaciones))
                        <p class="text-red-500 text-xs mt-2">No se encontraron asignaciones.</p>
                    @endif
                </div>

                <!-- TABLA DE PERMISOS -->
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                        Permisos por vista
                    </h3>

                    @if(empty($vistas))
                        <div class="text-center py-10 text-gray-400">
                            <p class="text-lg">No hay vistas disponibles</p>
                            <p class="text-sm mt-1">Verifica la conexión con la API</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Vista
                                        </th>
                                        @foreach($acciones as $accion)
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                                {{ $accion['nombre'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($vistas as $vista)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <td class="px-6 py-4 font-semibold text-gray-700">
                                                {{ $vista['nombre'] ?? 'Sin nombre' }}
                                            </td>
                                            @foreach($acciones as $accion)
                                                <td class="px-6 py-4 text-center">
                                                    <input
                                                        type="checkbox"
                                                        name="permisos[]"
                                                        value="{{ ($vista['nombre'] ?? '') . '-' . $accion['nombre'] }}"
                                                        class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                                                    >
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
                        class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                        ← Volver
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors">
                        Guardar Permisos
                    </button>
                </div>

            </form>
        </div>
    </main>
</x-app-layout>