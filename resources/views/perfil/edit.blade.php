<x-app-layout>
    <form action="{{ route('perfil.update', $usuarios->id_usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <main class="p-6 max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Editar Usuario</h2>
                    <p class="text-blue-100 text-sm">Modifica los datos del usuario</p>
                </div>

                <div class="p-8 grid grid-cols-1 gap-6">

                    <!-- Mostrar errores -->
                    @if($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Nombre -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nombre Completo</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $usuarios->nombre) }}" 
                               placeholder="Nombre completo"
                               class="w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo', $usuarios->correo) }}" 
                               placeholder="Correo electrónico"
                               class="w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $usuarios->telefono) }}" 
                               placeholder="Teléfono"
                               class="w-full border rounded px-4 py-2">
                    </div>

                    <!-- Estado (Activo/Inactivo) -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Estado</label>
                        <select name="activo" class="w-full border rounded px-4 py-2">
                            <option value="1" {{ old('activo', $usuarios->activo) == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('activo', $usuarios->activo) == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- Contraseña (opcional) -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nueva Contraseña (opcional)</label>
                        <input type="password" name="clave" placeholder="Dejar en blanco para no cambiar"
                               class="w-full border rounded px-4 py-2">
                    </div>

                    <!-- Botón Actualizar -->
                    <div class="flex justify-end mt-4">
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl font-semibold hover:scale-105 transition-transform duration-200">
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </form>
</x-app-layout>