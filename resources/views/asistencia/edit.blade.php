<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Programación - Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <main class="p-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Editar Programación</h1>
                <p class="text-gray-600">Modificar asignación de servidor a actividad</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('asistencia.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 py-2 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Listado
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Editar Programación</h2>
                <p class="text-blue-100 text-sm">Modifica los datos de la programación existente</p>
            </div>

<form action="{{ route('programacion.update', $programacion->id_programacion) }}" method="POST">                @csrf
                @method('PUT')
                
                <!-- Información Actual -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Información Actual
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Servidor:</span>
                            <p class="text-gray-900">{{ $programacion->asignacion->usuario->nombre }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Ministerio:</span>
                            <p class="text-gray-900">{{ $programacion->asignacion->ministerio->nombre_ministerio }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Rol:</span>
                            <p class="text-gray-900">{{ $programacion->asignacion->rol->nombre_rol }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-6">
                        <!-- Asignación (Servidor + Ministerio + Rol) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-green-500"></i>Servidor Asignado *
                            </label>
                            <select name="id_asignacion" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                                <option value="">Selecciona un servidor</option>
                                @foreach($asignaciones as $asignacion)
                                    <option value="{{ $asignacion->id_asignacion }}" 
                                        {{ old('id_asignacion', $programacion->id_asignacion) == $asignacion->id_asignacion ? 'selected' : '' }}>
                                        {{ $asignacion->usuario->nombre }} - 
                                        {{ $asignacion->ministerio->nombre_ministerio }} - 
                                        {{ $asignacion->rol->nombre_rol }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_asignacion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actividad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Actividad *
                            </label>
                            <select name="id_actividad" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                                <option value="">Selecciona una actividad</option>
                                @foreach($actividades as $actividad)
                                    <option value="{{ $actividad->id_actividad }}" 
                                        {{ old('id_actividad', $programacion->id_actividad) == $actividad->id_actividad ? 'selected' : '' }}>
                                        {{ $actividad->nombre_actividad }} - {{ $actividad->ministerio->nombre_ministerio }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_actividad')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-6">
                        <!-- Fecha -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-day mr-2 text-red-500"></i>Fecha *
                            </label>
                            <input type="date" name="fecha" value="{{ old('fecha', $programacion->fecha) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            @error('fecha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Horarios -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-indigo-500"></i>Hora Inicio *
                                </label>
                                <input type="time" name="hora_inicio" value="{{ old('hora_inicio', $programacion->hora_inicio) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-purple-500"></i>Hora Fin *
                                </label>
                                <input type="time" name="hora_fin" value="{{ old('hora_fin', $programacion->hora_fin) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            </div>
                        </div>
                        @error('hora_inicio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @error('hora_fin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-2 text-yellow-500"></i>Estado *
                            </label>
                            <select name="estado" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                                <option value="Programado" {{ old('estado', $programacion->estado) == 'Programado' ? 'selected' : '' }}>Programado</option>
                                <option value="Confirmado" {{ old('estado', $programacion->estado) == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="Reemplazo Solicitado" {{ old('estado', $programacion->estado) == 'Reemplazo Solicitado' ? 'selected' : '' }}>Reemplazo Solicitado</option>
                                <option value="Cancelado" {{ old('estado', $programacion->estado) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <!-- Botón Eliminar -->
                    <button type="button" onclick="confirmarEliminacion()" class="w-full sm:w-auto bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 font-medium mb-3 sm:mb-0">
                        <i class="fas fa-trash mr-2"></i>Eliminar Programación
                    </button>

                    <!-- Botones Derecha -->
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <a href="{{ route('asistencia.index') }}" class="w-full sm:w-auto bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 font-medium text-center">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300 transform hover:scale-105 font-medium">
                            <i class="fas fa-save mr-2"></i>Actualizar Programación
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulario de Eliminación (oculto) -->
           <form id="deleteForm" action="{{ route('programacion.destroy', $programacion->id_programacion) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </main>

    <script>
        // Validación de horarios en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const horaInicio = document.querySelector('input[name="hora_inicio"]');
            const horaFin = document.querySelector('input[name="hora_fin"]');
            
            function validarHorarios() {
                if (horaInicio.value && horaFin.value && horaInicio.value >= horaFin.value) {
                    horaFin.setCustomValidity('La hora de fin debe ser mayor que la hora de inicio');
                } else {
                    horaFin.setCustomValidity('');
                }
            }
            
            horaInicio.addEventListener('change', validarHorarios);
            horaFin.addEventListener('change', validarHorarios);
            
            // Validar inicialmente
            validarHorarios();
        });

        // Confirmación para eliminar
        function confirmarEliminacion() {
            if (confirm('¿Estás seguro de que deseas eliminar esta programación? Esta acción no se puede deshacer.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Mostrar alertas de éxito/error
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
</body>
</html>