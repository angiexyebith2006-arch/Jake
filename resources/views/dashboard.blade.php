<x-app-layout>
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-8px); }
    }
    @keyframes pulse-ring {
        0%   { transform: scale(1);    opacity: 1; }
        100% { transform: scale(1.4);  opacity: 0; }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }

    .card-modulo {
        animation: fadeInUp 0.4s ease both;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-modulo:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    .avatar-float { animation: float 3s ease-in-out infinite; }
    .pulse-dot::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 9999px;
        animation: pulse-ring 1.5s ease-out infinite;
        background: currentColor;
        opacity: 0.3;
    }
    .shimmer-text {
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 2s linear infinite;
    }
</style>

<main class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HERO CARD --}}
    <div class="relative bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-400 rounded-3xl shadow-2xl overflow-hidden">

        {{-- Fondo decorativo --}}
        <div class="absolute top-0 right-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full translate-y-16 -translate-x-10"></div>

        <div class="relative px-8 py-8 flex flex-col sm:flex-row items-center gap-6">

            {{-- Avatar flotante --}}
            <div class="avatar-float relative shrink-0">
                <div class="w-24 h-24 rounded-full bg-white shadow-2xl flex items-center justify-center text-yellow-500 font-black text-4xl border-4 border-yellow-300">
                    {{ strtoupper(substr(session('usuario_api.nombre', 'U'), 0, 1)) }}
                </div>
                {{-- Punto verde online --}}
                <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white pulse-dot relative"></div>
            </div>

            <div class="text-center sm:text-left">
                <p class="text-yellow-900 text-sm font-semibold tracking-wide uppercase">👋 Hola de nuevo,</p>
                <h1 class="text-3xl sm:text-4xl font-black text-white drop-shadow mt-1">
                    {{ session('usuario_api.nombre', 'Usuario') }}
                </h1>
                <p class="text-yellow-100 text-sm mt-1">
                    📧 {{ session('usuario_api.correo', '') }}
                </p>
                <div class="mt-3 inline-flex items-center gap-2 bg-white bg-opacity-20 backdrop-blur rounded-full px-4 py-1.5 text-white text-xs font-semibold">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    Sesión activa · {{ now()->format('d M Y, H:i') }}
                </div>
            </div>

            {{-- Sticker decorativo --}}
            <div class="hidden lg:flex ml-auto flex-col items-center gap-2 text-6xl select-none">
                <span style="animation: float 2.5s ease-in-out infinite;">✨</span>
                <span style="animation: float 3s ease-in-out infinite 0.5s;">🎯</span>
            </div>

        </div>
    </div>

    {{-- MÓDULOS --}}
    <div>
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-1">
            🚀 Módulos del sistema
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

            {{-- Usuarios --}}
            <a href="{{ route('perfil.index') }}"
                class="card-modulo group bg-white border-2 border-yellow-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 0ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">👥</div>
                <div class="w-10 h-1 bg-yellow-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Usuarios</span>
            </a>

            {{-- Autorizaciones --}}
            <a href="{{ route('autorizaciones.index') }}"
                class="card-modulo group bg-white border-2 border-blue-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 60ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">📋</div>
                <div class="w-10 h-1 bg-blue-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Autorizaciones</span>
            </a>

            {{-- Asistencia --}}
            <a href="{{ route('asistencia.index') }}"
                class="card-modulo group bg-white border-2 border-green-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 120ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">✅</div>
                <div class="w-10 h-1 bg-green-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Asistencia</span>
            </a>

            {{-- Programación --}}
            <a href="{{ route('programacion.index') }}"
                class="card-modulo group bg-white border-2 border-purple-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 180ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">📅</div>
                <div class="w-10 h-1 bg-purple-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Programación</span>
            </a>

            {{-- Finanzas --}}
            <a href="{{ route('finanzas.index') }}"
                class="card-modulo group bg-white border-2 border-emerald-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 240ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">💰</div>
                <div class="w-10 h-1 bg-emerald-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Finanzas</span>
            </a>

            {{-- Chat Grupal --}}
            <a href="{{ route('chatgrupal.index') }}"
                class="card-modulo group bg-white border-2 border-indigo-100 rounded-2xl p-5 flex flex-col items-center gap-3 cursor-pointer"
                style="animation-delay: 300ms">
                <div class="text-4xl group-hover:scale-125 transition-transform duration-300">💬</div>
                <div class="w-10 h-1 bg-indigo-400 rounded-full"></div>
                <span class="text-xs font-bold text-gray-700 text-center">Chat Grupal</span>
            </a>

        </div>
    </div>

    {{-- STATS DECORATIVAS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border-2 border-yellow-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="text-3xl">🏛️</div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Sistema</p>
                <p class="text-sm font-bold text-gray-800">JAKE Ministerios</p>
            </div>
        </div>

        <div class="bg-white border-2 border-green-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="text-3xl">📆</div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Fecha de hoy</p>
                <p class="text-sm font-bold text-gray-800">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</p>
            </div>
        </div>

        <div class="bg-white border-2 border-blue-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="text-3xl">⚡</div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Estado</p>
                <p class="text-sm font-bold text-green-600">Todo operativo</p>
            </div>
        </div>

    </div>

</main>
</x-app-layout>