<x-app-layout>

    <form action="{{ route('permisos.update', $idAsignacion) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="asignacion_id" value="{{ $idAsignacion }}">

        <main class="p-6 max-w-7xl mx-auto">

            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

                <!-- HEADER -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">Editar Permisos</h2>
                    <p class="text-green-100 text-sm">Modifica los permisos por vista y acción</p>
                </div>

                <div class="p-6">

                    <!-- USUARIO -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-700 mb-2">
                            Seleccionar Asignación
                        </label>
                        <input type="text"
                               value="#{{ $idAsignacion }} — {{ $permiso['usuarioNombre'] ?? ('Asignación #' . $idAsignacion) }}"
                               class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                               readonly>
                    </div>

                    @php
                        $listaVistas = [
                            'usuarios',
                            'programacion',
                            'asistencia',
                            'finanzas',
                            'chat Grupal',
                            'autorizaciones',
                        ];

                        $listaAcciones = [
                            'crear',
                            'editar',
                            'eliminar',
                            'ver',
                            'responder',
                        ];
                    @endphp

                    <!-- TABLA -->
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 rounded-xl overflow-hidden">

                            <thead class="bg-gray-100">
                                <tr class="text-gray-700 text-sm uppercase">
                                    <th class="px-4 py-3 text-left">Vista</th>
                                    @foreach($listaAcciones as $accion)
                                        <th class="px-4 py-3 text-center">{{ ucfirst($accion) }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($listaVistas as $vista)
                                    <tr class="border-t hover:bg-gray-50">

                                        <td class="px-4 py-4 font-semibold text-gray-700 capitalize">
                                            {{ $vista }}
                                        </td>

                                        @foreach($listaAcciones as $accion)
                                            @php
                                                $accionesVista = isset($permisos[$vista])
                                                    ? array_unique($permisos[$vista])
                                                    : [];

                                                $checked = in_array($accion, $accionesVista);
                                            @endphp

                                            <td class="text-center py-4">
                                                <input type="checkbox"
                                                       name="permisos[{{ $vista }}][]"
                                                       value="{{ $accion }}"
                                                       {{ $checked ? 'checked' : '' }}
                                                       class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            </td>
                                        @endforeach

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <!-- BOTONES -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('permisos.index') }}"
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            ← Volver
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:scale-105 transition-transform duration-200">
                            Actualizar Permisos
                        </button>
                    </div>

                </div>
            </div>

        </main>
    </form>

</x-app-layout>