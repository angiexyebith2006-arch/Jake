<x-app-layout>

<main class="p-6 max-w-7xl mx-auto">

    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-3xl mx-auto">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Mi Perfil</h2>
                <p class="text-yellow-800 text-sm mt-1">Tu información de cuenta</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                ← Volver
            </a>
        </div>

        {{-- AVATAR + NOMBRE --}}
        <div class="flex flex-col items-center py-8 border-b border-gray-100 bg-yellow-50">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-4xl shadow-lg mb-4">
                {{ strtoupper(substr(session('usuario_api.nombre', 'U'), 0, 1)) }}
            </div>
            <h3 class="text-2xl font-bold text-gray-800">
                {{ session('usuario_api.nombre', 'Usuario') }}
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                {{ session('usuario_api.correo', '') }}
            </p>
        </div>

        {{-- DATOS --}}
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- ID --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ID Usuario</p>
                <p class="text-sm font-semibold text-gray-800">
                    #{{ session('usuario_api.id_usuario', '—') }}
                </p>
            </div>

            {{-- Nombre --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nombre completo</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ session('usuario_api.nombre', '—') }}
                </p>
            </div>

            {{-- Correo --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 sm:col-span-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</p>
                <p class="text-sm font-semibold text-gray-800 break-all">
                    {{ session('usuario_api.correo', '—') }}
                </p>
            </div>

        </div>

        {{-- ACCIONES --}}
        <div class="px-6 pb-6 flex gap-3 justify-end border-t border-gray-100 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl font-semibold text-sm transition-colors">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </div>
</main>

</x-app-layout>