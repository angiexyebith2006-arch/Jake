<x-app-layout>
    <form action="{{ route('programacion.store') }}" method="POST">
        @csrf

        <main class="p-6 max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Crear Programación</h2>
                    <p class="text-blue-100 text-sm">Registra una nueva actividad programada</p>
                </div>

                <div class="p-8">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
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

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <!-- ACTIVIDAD -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tasks mr-1"></i> Actividad
                            </label>
                            <select name="id_actividad" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                <option value="">Seleccione una actividad</option>
                                @foreach ($actividades as $act)
                                    <option value="{{ $act->id_actividad }}" {{ old('id_actividad') == $act->id_actividad ? 'selected' : '' }}>
                                        {{ $act->nombre_actividad }} ({{ $act->ministerio }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_actividad')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- ASIGNACIÓN -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users mr-1"></i> Asignación
                            </label>
                            <select name="id_asignacion" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                <option value="">Seleccione una asignación</option>
                                @foreach ($asignaciones as $asig)
                                    <option value="{{ $asig->id_asignacion }}" {{ old('id_asignacion') == $asig->id_asignacion ? 'selected' : '' }}>
                                        {{ $asig->nombreUsuario ?? 'Usuario desconocido' }} — 
                                        {{ $asig->nombreRol ?? 'Rol desconocido' }} 
                                        ({{ $asig->nombreMinisterio ?? 'Sin ministerio' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_asignacion')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- FECHA -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-day mr-1"></i> Fecha
                            </label>
                            <input type="date" name="fecha" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                   value="{{ old('fecha') }}" 
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('fecha')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- HORA INICIO -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock mr-1"></i> Hora de Inicio
                            </label>
                            <input type="time" name="hora_inicio" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                   value="{{ old('hora_inicio') }}"
                                   required>
                            @error('hora_inicio')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- HORA FIN -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock mr-1"></i> Hora de Fin
                            </label>
                            <input type="time" name="hora_fin" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                   value="{{ old('hora_fin') }}"
                                   required>
                            @error('hora_fin')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- ESTADO -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Estado
                            </label>
                            <select name="estado" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Confirmado" {{ old('estado') == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="Reemplazado" {{ old('estado') == 'Reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                            </select>
                            @error('estado')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <!-- BOTONES -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-10 pt-6 border-t border-gray-200">
                        <a href="{{ route('programacion.index') }}" 
                           class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl shadow-lg font-semibold text-center transition-all duration-300">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>

                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i> Guardar
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </form>

    <script>
        // Validación de fechas
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.querySelector('input[name="fecha"]');
            const hoy = new Date().toISOString().split('T')[0];
            fechaInput.min = hoy;
            
            // Si el usuario selecciona una fecha pasada, mostrar alerta
            fechaInput.addEventListener('change', function() {
                if (this.value < hoy) {
                    alert('La fecha no puede ser anterior a hoy.');
                    this.value = hoy;
                }
            });

            // Validación de horas
            const horaInicio = document.querySelector('input[name="hora_inicio"]');
            const horaFin = document.querySelector('input[name="hora_fin"]');
            
            horaFin.addEventListener('change', function() {
                if (horaInicio.value && this.value <= horaInicio.value) {
                    alert('La hora de fin debe ser mayor a la hora de inicio.');
                    this.value = '';
                }
            });
        });
    </script>
</x-app-layout>