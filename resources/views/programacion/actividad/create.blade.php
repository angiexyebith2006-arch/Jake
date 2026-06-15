<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4 rounded-t-xl">
            <h2 class="text-xl font-bold text-white">Crear Actividad</h2>
            <p class="text-green-100 text-sm">Registrar nueva actividad</p>
        </div>

        <!-- FORM -->
        <div class="bg-white shadow-xl rounded-b-xl p-6">

            <form action="{{ route('actividades.store') }}" method="POST">
                @csrf

                <!-- NOMBRE -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input
                        type="text"
                        name="nombre_actividad"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                        required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea
                        name="descripcion"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                        required></textarea>
                </div>

                <!-- HORAS -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                        <input
                            type="time"
                            name="hora_inicio"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Fin</label>
                        <input
                            type="time"
                            name="hora_fin"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="bg-green-600 text-white px-5 py-2 rounded-lg shadow hover:bg-green-700">
                        Guardar
                    </button>

                    <a href="{{ route('actividades.index') }}"
                        class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600">
                        Volver
                    </a>
                </div>

            </form>

        </div>

    </div>
</x-app-layout>