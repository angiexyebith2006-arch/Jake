<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-white">Editar Usuario</h2>
                        <p class="text-blue-100 text-sm">Modifica los datos del usuario</p>
                    </div>
                    <a href="{{ route('perfil.index') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('perfil.update', $usuarios->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    
                    <!-- Mostrar errores -->
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong class="font-semibold">Errores encontrados:</strong>
                            </div>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nombre Completo
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $usuarios->nombre) }}" 
                               placeholder="Nombre completo del usuario"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Correo Electrónico
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="correo" 
                               value="{{ old('correo', $usuarios->correo) }}" 
                               placeholder="correo@ejemplo.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-blue-500"></i>Teléfono
                        </label>
                        <input type="text" 
                               name="telefono" 
                               value="{{ old('telefono', $usuarios->telefono) }}" 
                               placeholder="Número de teléfono"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-xs text-gray-500 mt-1">Formato: 9XXXXXXXX (opcional)</p>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-2 text-blue-500"></i>Estado
                        </label>
                        <select name="activo" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="1" {{ old('activo', $usuarios->activo) == '1' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle text-green-500 mr-2"></i> Activo
                            </option>
                            <option value="0" {{ old('activo', $usuarios->activo) == '0' ? 'selected' : '' }}>
                                <i class="fas fa-ban text-red-500 mr-2"></i> Inactivo
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Los usuarios inactivos no podrán acceder al sistema</p>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key mr-2 text-blue-500"></i>Nueva Contraseña
                        </label>
                        <input type="password" 
                               name="clave" 
                               placeholder="Dejar en blanco para no cambiar"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres (opcional)</p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('perfil.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg hover:from-blue-700 hover:to-indigo-800 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Actualizar Usuario
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>
</x-app-layout>