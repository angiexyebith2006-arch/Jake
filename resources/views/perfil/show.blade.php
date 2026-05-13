<x-app-layout>

<main class="p-6 max-w-7xl mx-auto">

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl text-sm font-medium">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-3xl mx-auto">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">Perfil de Usuario</h2>
                <p class="text-blue-100 text-sm mt-1">Información detallada del servidor</p>
            </div>
            <a href="{{ route('perfil.index') }}"
                class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                ← Volver
            </a>
        </div>

        {{-- AVATAR + NOMBRE --}}
        <div class="flex flex-col items-center py-8 border-b border-gray-100">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-3xl shadow-lg mb-4">
                {{ strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)) }}
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $usuario['nombre'] ?? '—' }}</h3>
            <span class="mt-2 px-3 py-1 rounded-full text-xs font-semibold
                {{ ($usuario['activo'] ?? false) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ($usuario['activo'] ?? false) ? '● Activo' : '● Inactivo' }}
            </span>
        </div>

        {{-- DATOS --}}
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- ID --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ID Usuario</p>
                <p class="text-sm font-semibold text-gray-800">#{{ $usuario['idUsuario'] ?? '—' }}</p>
            </div>

            {{-- Correo --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</p>
                <p class="text-sm font-semibold text-gray-800 break-all">{{ $usuario['correo'] ?? '—' }}</p>
            </div>

            {{-- Teléfono --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                <p class="text-sm font-semibold text-gray-800">{{ $usuario['telefono'] ?? 'No registrado' }}</p>
            </div>

            {{-- Estado --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estado</p>
                <p class="text-sm font-semibold {{ ($usuario['activo'] ?? false) ? 'text-green-600' : 'text-red-600' }}">
                    {{ ($usuario['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                </p>
            </div>

        </div>

        {{-- ACCIONES --}}
        <div class="px-6 pb-6 flex gap-3 justify-end border-t border-gray-100 pt-4">
            <a href="{{ route('perfil.edit', $usuario['idUsuario']) }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-semibold text-sm transition-colors">
                Editar usuario
            </a>
            <form action="{{ route('perfil.destroy', $usuario['idUsuario']) }}" method="POST"
                onsubmit="return confirm('¿Eliminar a {{ $usuario['nombre'] }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl font-semibold text-sm transition-colors">
                    Eliminar
                </button>
            </form>
        </div>

    </div>
</main>

</x-app-layout>