<x-app-layout>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-modulo{
        animation: fadeInUp .4s ease both;
        transition: .25s ease;
    }

    .card-modulo:hover{
        transform: translateY(-6px);
        box-shadow: 0 20px 35px rgba(0,0,0,.10);
    }
</style>

<main class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HERO --}}
    <div class="relative overflow-hidden rounded-3xl shadow-2xl min-h-[320px]">

        {{-- Fondo imagen --}}
        <div class="absolute inset-0">

            {{-- IMPORTANTE:
                 guarda tu imagen en:
                 public/images/grupo.jpg
            --}}
            <img 
                src="{{ asset('images/imagen2.png') }}"
                class="w-full h-full object-cover"
                alt="Grupo"
            >

            {{-- Overlay oscuro --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/45 to-black/20"></div>

        </div>

        {{-- Contenido --}}
        <div class="relative z-10 h-full px-8 py-10 flex flex-col lg:flex-row items-center gap-8">

            {{-- Avatar --}}
            <div class="relative shrink-0">

                <div class="w-32 h-32 rounded-full border-4 border-white flex items-center justify-center text-white font-black text-6xl bg-white/10 backdrop-blur shadow-2xl">
                    {{ strtoupper(substr(session('usuario_api.nombre', 'U'), 0, 1)) }}
                </div>

                {{-- Online --}}
                <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-400 border-4 border-white rounded-full"></div>

            </div>

            {{-- Texto --}}
            <div class="flex-1 text-center lg:text-left">

                <p class="text-yellow-300 font-semibold text-sm uppercase tracking-widest">
                    👋 Hola de nuevo
                </p>

                <h1 class="text-5xl font-black text-white mt-2 drop-shadow-lg">
                    {{ session('usuario_api.nombre', 'Usuario') }}
                </h1>

                <p class="mt-3 text-gray-200 flex items-center gap-2 justify-center lg:justify-start text-lg">
                    ✉ {{ session('usuario_api.correo', '') }}
                </p>

                {{-- Badge --}}
                <div class="mt-5 inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-5 py-2 rounded-full text-white text-sm font-semibold border border-white/20">

                    <span class="w-3 h-3 rounded-full bg-green-400"></span>

                    Sesión activa · {{ now()->format('d M Y, H:i A') }}

                </div>

            </div>

        </div>

    </div>



    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Sistema --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">

            <div class="w-14 h-14 rounded-full bg-yellow-100 flex items-center justify-center text-2xl">
                🏛️
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold">
                    Sistema
                </p>

                <h3 class="font-bold text-gray-800 mt-1">
                    JAKE Ministerios
                </h3>
            </div>

        </div>

        {{-- Fecha --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">

            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-2xl">
                📆
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold">
                    Fecha de hoy
                </p>

                <h3 class="font-bold text-gray-800 mt-1">
                    {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                </h3>
            </div>

        </div>

        {{-- Estado --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">

            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-2xl">
                ⚡
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold">
                    Estado
                </p>

                <h3 class="font-bold text-green-600 mt-1">
                    Todo operativo
                </h3>
            </div>

        </div>

    </div>

</main>

</x-app-layout>