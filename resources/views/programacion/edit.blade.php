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
                                <strong>Error:</strong>
                            </div>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('programacion.update', $programacion->id_programacion ?? $programacion->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- FECHA --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                                <input type="date" name="fecha" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('fecha', isset($programacion->fecha) ? \Carbon\Carbon::parse($programacion->fecha)->format('Y-m-d') : '') }}" required>
                            </div>

                            {{-- ACTIVIDAD --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Actividad *</label>
                                @if($usandoApiJava ?? env('JAVA_API_BASE_URL'))
                                    <select name="id_actividad" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Seleccione una actividad...</option>
                                        @foreach($actividades as $actividad)
                                            @php
                                                $actividadId = $actividad['id_actividad'] ?? $actividad->id_actividad;
                                                $actividadNombre = $actividad['nombre_actividad'] ?? $actividad->nombre_actividad;
                                                $programacionActividadId = $programacion->id_actividad ?? ($programacion->actividad->id_actividad ?? null);
                                            @endphp
                                            <option value="{{ $actividadId }}"
                                                {{ $actividadId == $programacionActividadId ? 'selected' : '' }}>
                                                {{ $actividadNombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="id_actividad" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Seleccione una actividad...</option>
                                        @foreach($actividades as $actividad)
                                            <option value="{{ $actividad->id_actividad }}"
                                                {{ $actividad->id_actividad == ($programacion->id_actividad ?? $programacion->actividad->id_actividad) ? 'selected' : '' }}>
                                                {{ $actividad->nombre_actividad }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            {{-- HORA INICIO --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio *</label>
                                <input type="time" name="hora_inicio" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('hora_inicio', $programacion->hora_inicio ?? '') }}" required>
                            </div>

                            {{-- HORA FIN --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fin *</label>
                                <input type="time" name="hora_fin" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('hora_fin', $programacion->hora_fin ?? '') }}" required>
                            </div>

                            {{-- ASIGNACIÓN --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Asignación *</label>
                                @if($usandoApiJava ?? env('JAVA_API_BASE_URL'))
                                    <div class="space-y-3">
                                        {{-- USUARIO --}}
                                        <select name="id_usuario" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Seleccione un servidor...</option>
                                            @foreach($usuarios as $usuario)
                                                @php
                                                    $usuarioId = $usuario['id_usuario'] ?? $usuario->id;
                                                    $usuarioNombre = $usuario['nombre'] ?? $usuario->nombre;
                                                    $programacionUsuarioId = $programacion->asignacion->usuario->id_usuario ?? null;
                                                @endphp
                                                <option value="{{ $usuarioId }}"
                                                    {{ $usuarioId == $programacionUsuarioId ? 'selected' : '' }}>
                                                    {{ $usuarioNombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        {{-- ROL --}}
                                        <select name="id_rol" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Seleccione un rol...</option>
                                            @foreach($roles as $rol)
                                                @php
                                                    $rolId = $rol['id_rol'] ?? $rol->id;
                                                    $rolNombre = $rol['nombre_rol'] ?? $rol->nombre;
                                                    $programacionRolId = $programacion->asignacion->rol->id_rol ?? null;
                                                @endphp
                                                <option value="{{ $rolId }}"
                                                    {{ $rolId == $programacionRolId ? 'selected' : '' }}>
                                                    {{ $rolNombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <select name="id_asignacion" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Seleccione una asignación...</option>
                                        @foreach($asignaciones as $asignacion)
                                            <option value="{{ $asignacion->id_asignacion }}"
                                                {{ $asignacion->id_asignacion == ($programacion->id_asignacion ?? '') ? 'selected' : '' }}>
                                                {{ $asignacion->usuario->nombre ?? 'Usuario' }} - 
                                                {{ $asignacion->rol->nombre_rol ?? 'Rol' }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            {{-- ESTADO --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @if($usandoApiJava ?? env('JAVA_API_BASE_URL'))
                                        <option value="Pendiente" {{ ($programacion->estado ?? '') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="Confirmado" {{ ($programacion->estado ?? '') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="Reemplazado" {{ ($programacion->estado ?? '') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                                    @else
                                        <option value="Programado" {{ ($programacion->estado ?? '') == 'Programado' ? 'selected' : '' }}>Programado</option>
                                        <option value="Reemplazado" {{ ($programacion->estado ?? '') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                                        <option value="Cancelado" {{ ($programacion->estado ?? '') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="Pendiente" {{ ($programacion->estado ?? '') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="Confirmado" {{ ($programacion->estado ?? '') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- MINISTERIO (solo informativo) --}}
                        @if(isset($programacion->actividad) && isset($programacion->actividad->ministerio))
                        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ministerio</label>
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-3 rounded-xl mr-3">
                                    <i class="fas fa-hands-praying text-purple-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">
                                    {{ $programacion->actividad->ministerio->nombre_ministerio ?? 'Sin ministerio' }}
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('programacion.index') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
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
            // Manejar el envío del formulario
            const form = document.getElementById('editForm');
            
            form.addEventListener('submit', function(e) {
                // Validación básica
                const horaInicio = document.querySelector('input[name="hora_inicio"]');
                const horaFin = document.querySelector('input[name="hora_fin"]');
                
                if (horaInicio.value && horaFin.value) {
                    if (horaInicio.value >= horaFin.value) {
                        e.preventDefault();
                        alert('La hora de inicio debe ser anterior a la hora de fin');
                        horaInicio.focus();
                        return false;
                    }
                }
                
                // Si todo está bien, permitir el envío
                // El controlador se encargará de redireccionar a index
                return true;
            });
            
            // Validación de fechas
            const fechaInput = document.querySelector('input[name="fecha"]');
            if (fechaInput) {
                const hoy = new Date().toISOString().split('T')[0];
                
                // Para ediciones, permitir fechas pasadas
                if (fechaInput.value && fechaInput.value < hoy) {
                    fechaInput.min = fechaInput.value;
                } else {
                    fechaInput.min = hoy;
                }
            }
        });
    </script>
</body>
</html>