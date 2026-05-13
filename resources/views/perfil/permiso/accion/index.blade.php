<x-app-layout>

    <div class="p-6">

         @if(session('success'))

        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">

            {{ session('success') }}

        </div>

    @endif

        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Acciones
                </h2>

                <p class="text-gray-500">
                    Gestión de acciones del sistema
                </p>
            </div>

            <a href="{{ route('acciones.crear') }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                + Nueva Acción
            </a>

        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left">
                            ID
                        </th>

                        <th class="p-4 text-left">
                            Nombre Acción
                        </th>

                        <th class="p-4 text-center">
                            Opciones
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($acciones as $accion)

                    <tr class="border-b">

                        <td class="p-4">
                            {{ $accion['idAccion'] }}
                        </td>

                        <td class="p-4 capitalize">
                            {{ $accion['nombreAccion'] }}
                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('acciones.editar', $accion['idAccion']) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg">
                                    Editar
                                </a>

                                <form action="{{ route('acciones.eliminar', $accion['idAccion']) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg">
                                        Eliminar
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>