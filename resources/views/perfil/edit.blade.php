<x-app-layout>
<form action="{{ route('perfil.update', $usuario->id_usuario) }}" method="POST"
      class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">
    @csrf
    @method('PUT')

    <h2 class="text-2xl font-bold mb-6">Editar Usuario</h2>

    <input type="text" name="nombre"
           value="{{ old('nombre', $usuario->nombre) }}"
           class="w-full mb-4 border rounded px-4 py-2">

    <input type="email" name="correo"
           value="{{ old('correo', $usuario->correo) }}"
           class="w-full mb-4 border rounded px-4 py-2">

    <input type="text" name="telefono"
           value="{{ old('telefono', $usuario->telefono) }}"
           class="w-full mb-4 border rounded px-4 py-2">

    <label class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
            <option class="text-gray-400">Seleccione un rol</option>
            <option class="text-gray-600">Alabanza</option>
            <option class="text-gray-600">Escuela Dominical</option>
            <option class="text-gray-600">Líder</option>
            <option class="text-gray-600">Voluntario</option>
        </select>

    <label class="block text-sm font-semibold text-gray-700 mb-2">Nivel Ministerial</label>
        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
            <option class="text-gray-400">Seleccione nivel</option>
            <option class="text-gray-600">Principiante</option>
            <option class="text-gray-600">Intermedio</option>
            <option class="text-gray-600">Avanzado</option>
            <option class="text-gray-600">Líder</option>
        </select>

    <div class="flex justify-end gap-4">
        <a href="{{ route('perfil.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
            Cancelar
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Guardar Cambios
        </button>
    </div>
</form>
</x-app-layout>
