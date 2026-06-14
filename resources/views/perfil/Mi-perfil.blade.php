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
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ID Usuario</p>
                <p class="text-sm font-semibold text-gray-800">
                    #{{ session('usuario_api.id_usuario', '—') }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nombre completo</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ session('usuario_api.nombre', '—') }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 sm:col-span-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</p>
                <p class="text-sm font-semibold text-gray-800 break-all">
                    {{ session('usuario_api.correo', '—') }}
                </p>
            </div>
        </div>

        {{-- ASIGNACIÓN --}}
        @php
            $asignacionesResp = \Illuminate\Support\Facades\Http::get('http://127.0.0.1:5431/api/asignaciones')->json();
            $asignacionesAll  = $asignacionesResp['data'] ?? $asignacionesResp ?? [];
            $usuarioId        = (int) session('usuario_api.id_usuario');
            $miAsignacion     = collect($asignacionesAll)->firstWhere('idUsuario', $usuarioId);
        @endphp

        <div class="px-6 pb-4 border-t border-gray-100 pt-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
                Asignación
            </h4>

            @if(empty($miAsignacion))
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center mb-4">
                    <p class="text-sm text-gray-400 italic">No tienes una asignación registrada aún.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">

                    {{-- ROL --}}
                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rol</p>
                            <p class="text-sm font-bold text-gray-800">{{ $miAsignacion['rolNombre'] ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- MINISTERIO --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ministerio</p>
                            <p class="text-sm font-bold text-gray-800">{{ $miAsignacion['ministerioNombre'] ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- CARGO --}}
                    <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Cargo</p>
                            <p class="text-sm font-bold text-gray-800">{{ $miAsignacion['cargoNombre'] ?? '—' }}</p>
                        </div>
                    </div>

                </div>
            @endif
        </div>

        {{-- MÓDULOS Y PERMISOS --}}
        @php
            $permisos = session('permisos_jake', []);
            $colorMap = [
                'ver'       => 'bg-blue-100 text-blue-700',
                'crear'     => 'bg-green-100 text-green-700',
                'editar'    => 'bg-yellow-100 text-yellow-700',
                'eliminar'  => 'bg-red-100 text-red-700',
                'responder' => 'bg-purple-100 text-purple-700',
            ];
        @endphp

        <div class="px-6 pb-4 border-t border-gray-100 pt-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Módulos y Permisos
            </h4>

            @if(empty($permisos))
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center mb-4">
                    <p class="text-sm text-gray-400 italic">No tienes permisos asignados aún.</p>
                </div>
            @else
                <div class="space-y-3 mb-4">
                    @foreach($permisos as $modulo => $acciones)
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2.5 flex items-center gap-2 border-b border-gray-100">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 17h16"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800 capitalize">{{ $modulo }}</span>
                        </div>
                        <div class="px-4 py-2.5 flex flex-wrap gap-2">
                            @foreach((array) $acciones as $accion)
                                @php $color = $colorMap[strtolower($accion)] ?? 'bg-gray-100 text-gray-600'; @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $color }}">
                                    {{ ucfirst($accion) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ACCIONES --}}
        <div class="px-6 pb-6 flex gap-3 justify-end border-t border-gray-100 pt-4">
            <a href="{{ route('solicitar.permisos') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-xl font-semibold text-sm transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Solicitar Permisos
            </a>
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