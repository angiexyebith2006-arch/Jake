<x-app-layout>

    <div class="p-6">

        @if(session('success'))

            <div id="alerta"
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">

                {{ session('success') }}

            </div>

            <script>
                setTimeout(() => {
                    document.getElementById('alerta').style.display = 'none';
                }, 3000);
            </script>

        @endif

        <div class="flex justify-between items-center mb-6">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Vistas
                </h2>

                <p class="text-gray-500">
                    Gestión de vistas del sistema
                </p>

            </div>

            <a href="{{ route('vistas.crear') }}"
               class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                + Nueva Vista
            </a>

        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Nombre</th>
                        <th class="p-4 text-center">Opciones</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($vistas as $vista)

                    <tr class="border-b">

                        <td class="p-4">
                            {{ $vista['idVista'] }}
                        </td>

                        <td class="p-4 capitalize">
                            {{ $vista['nombre'] }}
                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('vistas.editar', $vista['idVista']) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg">
                                    Editar
                                </a>

                                <form action="{{ route('vistas.eliminar', $vista['idVista']) }}"
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