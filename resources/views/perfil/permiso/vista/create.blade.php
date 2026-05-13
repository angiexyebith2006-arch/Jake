<x-app-layout>

    <div class="p-6 max-w-xl mx-auto">

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-2xl font-bold mb-6">
                Crear Vista
            </h2>

            <form action="{{ route('vistas.guardar') }}"
                  method="POST">

                @csrf

                <div class="mb-4">

                    <label class="block mb-2 font-semibold">
                        Nombre de la Vista
                    </label>

                    <input type="text"
                           name="nombre"
                           class="w-full border rounded-lg p-3"
                           placeholder="Ejemplo: usuarios">

                </div>

                <div class="flex gap-2">

                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Guardar
                    </button>

                    <a href="{{ route('vistas.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>