<x-app-layout>
    <div class="max-w-md mx-auto mt-16 p-6 bg-white rounded-xl shadow-md ring-1 ring-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Nueva Programación</h2>

        <form action="{{ route('programacion.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Actividad -->
            <div>
                <label for="id_actividad" class="block text-sm font-semibold text-gray-700 mb-1">Actividad</label>
                <select id="id_actividad" name="id_actividad" required
                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
                    <option value="">Selecciona una actividad</option>
                    @foreach($actividades as $actividad)
                        <option value="{{ $actividad->id_actividad }}">{{ $actividad->nombre_actividad }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Asignación -->
            <div>
                <label for="id_asignacion" class="block text-sm font-semibold text-gray-700 mb-1">Asignación</label>
                <select id="id_asignacion" name="id_asignacion" required
                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
                    <option value="">Selecciona una asignación</option>
                    @foreach($asignaciones as $asignacion)
                        <option value="{{ $asignacion->id_asignacion }}">
                            {{ $asignacion->usuario->nombre }} - {{ $asignacion->rol->nombre_rol }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                <input type="date" id="fecha" name="fecha" required
                       class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
            </div>

            <!-- Hora Inicio -->
            <div>
                <label for="hora_inicio" class="block text-sm font-semibold text-gray-700 mb-1">Hora Inicio</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required
                       class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
            </div>

            <!-- Hora Fin -->
            <div>
                <label for="hora_fin" class="block text-sm font-semibold text-gray-700 mb-1">Hora Fin</label>
                <input type="time" id="hora_fin" name="hora_fin" required
                       class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                <select id="estado" name="estado" required
                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-400">
                    <option value="Reemplazado">Reemplazado</option>
                    <option value="Confirmado">Confirmado</option>
                    <option value="Pendiente">Pendiente</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full mt-3 rounded-md bg-indigo-600 px-4 py-2 text-white font-semibold shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                Guardar
            </button>
        </form>
    </div>
</x-app-layout>
