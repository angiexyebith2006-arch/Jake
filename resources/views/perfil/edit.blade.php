<x-app-layout>
    <form action="{{ route('perfil.update', $usuarios->idUsuario) }}" method="POST">
        @csrf
        @method('PUT')

        <main class="p-6 max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
                
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Información del Usuario</h2>
                    <p class="text-blue-100 text-sm">Actualiza los datos del perfil y configura los permisos</p>
                </div>

                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $usuarios->nombre) }}"
                                   class="w-full border rounded px-4 py-2">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" name="correo" value="{{ old('correo', $usuarios->correo) }}"
                                   class="w-full border rounded px-4 py-2">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $usuarios->telefono) }}"
                                   class="w-full border rounded px-4 py-2">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Estado</label>
                            <select name="activo" class="w-full border rounded px-4 py-2">
                                <option value="1" {{ $usuarios->activo == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $usuarios->activo == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Contraseña</label>
                            <input type="password" name="clave" class="w-full border rounded px-4 py-2">
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="text-right mt-4">
                    <a href="{{ route('perfil.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </main>
    </form>
</x-app-layout>
