<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Programación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
<x-app-layout>
<main class="p-6 max-w-4xl mx-auto">

<div class="mb-6">
    <a href="{{ route('programacion.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Volver a Programaciones
    </a>
    
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Editar Programación</h1>
    <p class="text-gray-600">Modifica los datos de la programación</p>
</div>

<div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
        <h2 class="text-xl font-bold text-white">Información de la Programación</h2>
    </div>
    
    <div class="p-6">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Errores encontrados:</strong>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('programacion.update', $programacion->id_programacion) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- FECHA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-day mr-1"></i> Fecha *
                    </label>
                    <input type="date" name="fecha" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('fecha', $programacion->fecha) }}" 
                           required>
                </div>

                {{-- ACTIVIDAD --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tasks mr-1"></i> Actividad *
                    </label>
                    <select name="id_actividad" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione una actividad...</option>
                        @foreach($actividades as $actividad)
                            <option value="{{ $actividad->id_actividad }}" {{ $actividad->id_actividad == old('id_actividad', $programacion->id_actividad) ? 'selected' : '' }}>
                                {{ $actividad->nombre_actividad }}
                                @if($actividad->hora_inicio && $actividad->hora_fin)
                                    ({{ substr($actividad->hora_inicio, 0, 5) }} - {{ substr($actividad->hora_fin, 0, 5) }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ASIGNACIÓN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-1"></i> Asignación *
                    </label>
                    <select name="id_asignacion" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione una asignación...</option>
                        @foreach($asignaciones as $asignacion)
                            <option value="{{ $asignacion->id_asignacion }}" {{ $asignacion->id_asignacion == old('id_asignacion', $programacion->id_asignacion) ? 'selected' : '' }}>
                                {{ $asignacion->texto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ESTADO --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i> Estado
                    </label>
                    <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Pendiente" {{ old('estado', $programacion->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Confirmado" {{ old('estado', $programacion->estado) == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                        <option value="Reemplazado" {{ old('estado', $programacion->estado) == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                    </select>
                </div>
            </div>

            {{-- Información adicional de la actividad (solo lectura) --}}
            @php
                $actividadSeleccionada = $actividades->firstWhere('id_actividad', $programacion->id_actividad);
            @endphp
            @if($actividadSeleccionada)
                <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-info-circle mr-1"></i> Información de la Actividad
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Horario:</span>
                            <span class="font-medium text-gray-800">
                                {{ substr($actividadSeleccionada->hora_inicio ?? '00:00', 0, 5) }} - 
                                {{ substr($actividadSeleccionada->hora_fin ?? '00:00', 0, 5) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Descripción:</span>
                            <span class="font-medium text-gray-800">{{ $actividadSeleccionada->descripcion ?? 'No disponible' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('programacion.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 transform hover:scale-105 flex items-center">
                    <i class="fas fa-save mr-2"></i> Actualizar Programación
                </button>
            </div>
        </form>

    </div>
</div>

</main>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Si quieres mostrar un preview del horario cuando cambia la actividad
        const selectActividad = document.querySelector('select[name="id_actividad"]');
        const infoHorario = document.querySelector('.bg-gray-50');
        
        if (selectActividad && infoHorario) {
            selectActividad.addEventListener('change', function() {
                // Puedes agregar lógica para actualizar la información de la actividad vía AJAX si es necesario
            });
        }
    });
</script>
</body>
</html>