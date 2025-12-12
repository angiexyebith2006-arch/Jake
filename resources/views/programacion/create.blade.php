<x-app-layout>
<form action="{{ route('programacion.store') }}" method="POST">
@csrf

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Programación</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

<main class="p-6 max-w-7xl mx-auto">

    <!-- Card principal -->
    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

        <!-- Encabezado con gradiente -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Crear Programación</h2>
            <p class="text-blue-100 text-sm">Registra una nueva actividad programada</p>
        </div>

        <div class="p-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- ACTIVIDAD -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Actividad</label>
                    <select name="id_actividad" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        <option value="">Seleccione una actividad</option>
                        @foreach ($actividades as $act)
                        <option value="{{ $act->id_actividad }}">
                            {{ $act->nombre_actividad }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- ASIGNACIÓN -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Asignación (Usuario - Rol)</label>
                    <select name="id_asignacion" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        <option value="">Seleccione una asignación</option>
                        @foreach ($asignaciones as $asig)
                        <option value="{{ $asig->id_asignacion }}">
                            {{ $asig->usuario->nombre ?? 'Usuario desconocido' }} — 
                            {{ $asig->rol->nombre_rol ?? 'Rol desconocido' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- FECHA -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="fecha" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                </div>

                <!-- HORA INICIO -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de Inicio</label>
                    <input type="time" name="hora_inicio" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                </div>

                <!-- HORA FIN -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de Fin</label>
                    <input type="time" name="hora_fin" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                </div>

                <!-- ESTADO -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        <option>Pendiente</option>
                        <option>Confirmado</option>
                        <option>Reemplazado</option>
                    </select>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-10 pt-6 border-t border-gray-200">
                <a href="{{ route('programacion.index') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold">
                    Cancelar
                </a>

                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold">
                    Guardar
                </button>
            </div>

        </div>

    </div>

</main>

</body>
</html>

</form>
</x-app-layout>
