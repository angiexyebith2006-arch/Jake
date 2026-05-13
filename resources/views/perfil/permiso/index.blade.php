<x-app-layout>
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fila-permiso { animation: fadeIn 0.25s ease both; }
</style>

<main class="p-6 max-w-7xl mx-auto">

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

                {{-- Título --}}
                <div>
                    <h2 class="text-white text-2xl font-bold">Permisos</h2>
                    <p class="text-green-100 text-sm mt-1">Control de acceso por usuario y módulo</p>
                </div>

                {{-- Botones + Buscador --}}
                <div class="flex flex-col lg:flex-row gap-3 lg:items-center w-full lg:w-auto">

                    {{-- Botones --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('perfil.index') }}"
                            class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver
                        </a>
                        <a href="{{ route('acciones.index') }}" 
                            class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            Acciones
                        </a>
                        <a href="{{ route('vistas.index') }}" 
                            class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            Vista
                        </a>
                        <a href="{{ route('permisos.create') }}"
                            class="inline-flex items-center gap-2 bg-white text-green-700 hover:bg-green-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuevo permiso
                        </a>
                    </div>

                    {{-- Buscador --}}
                    <div class="relative w-full lg:w-80">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-green-300 pointer-events-none"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input id="filtro" type="text" placeholder="Buscar por usuario o vista..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-green-400 bg-white shadow-sm text-sm placeholder-gray-400 outline-none focus:border-green-300 focus:ring-1 focus:ring-green-300 transition-all">
                    </div>

                </div>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
            <div class="text-sm text-gray-600">
                <i class="fas fa-lock mr-1"></i>
                Mostrando <span id="totalVisibles" class="font-semibold">{{ count($agrupados) }}</span> permisos
            </div>
        </div>

        {{-- TABLA --}}
        @if(count($agrupados) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-56">
                            <i class="fas fa-user mr-1 text-green-500"></i> Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">
                            <i class="fas fa-eye mr-1 text-green-500"></i> Vista
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1 text-green-500"></i> Acciones
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-40">
                            <i class="fas fa-cog mr-1"></i> Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100" id="tabla-body">
                    @foreach($agrupados as $grupo)
                        @foreach($grupo['vistas'] as $vistaNombre => $info)
                            <tr class="fila-permiso hover:bg-green-50 transition-colors"
                                data-nombre="{{ strtolower($grupo['nombre']) }}"
                                data-vista="{{ strtolower($vistaNombre) }}"
                                style="animation-delay: {{ ($loop->parent->index * 5 + $loop->index) * 30 }}ms">

                                {{-- USUARIO (solo primera fila del grupo) --}}
                                @if($loop->first)
                                    <td class="px-6 py-4 align-top" rowspan="{{ count($grupo['vistas']) }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                                {{ strtoupper(substr($grupo['nombre'], 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $grupo['nombre'] }}</p>
                                                <p class="text-xs text-gray-400">ID: {{ $grupo['idAsignacion'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                {{-- VISTA --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-700">{{ ucfirst($vistaNombre) }}</span>
                                </td>

                                {{-- ACCIONES --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($info['acciones'] as $accion)
                                            @php
                                                $badge = match($accion) {
                                                    'crear'     => 'bg-blue-100 text-blue-700',
                                                    'editar'    => 'bg-amber-100 text-amber-700',
                                                    'eliminar'  => 'bg-red-100 text-red-700',
                                                    'ver'       => 'bg-purple-100 text-purple-700',
                                                    'responder' => 'bg-teal-100 text-teal-700',
                                                    default     => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $badge }}">
                                                <i class="fas 
                                                    @if($accion == 'crear') fa-plus
                                                    @elseif($accion == 'editar') fa-edit
                                                    @elseif($accion == 'eliminar') fa-trash-alt
                                                    @elseif($accion == 'ver') fa-eye
                                                    @elseif($accion == 'responder') fa-reply
                                                    @else fa-circle @endif mr-1"></i>
                                                {{ ucfirst($accion) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- BOTONES estilo enlaces --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('permisos.edit', $info['id']) }}" 
                                       class="text-green-600 hover:text-green-900" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button onclick="eliminarPermiso({{ $info['id'] }})"
                                            class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </td>

                            </tr>
                        @endforeach

                        {{-- SEPARADOR ENTRE USUARIOS --}}
                        @if(!$loop->last)
                            <tr><td colspan="4" class="bg-gray-50 py-1"></td></tr>
                        @endif

                    @endforeach
                </tbody>
            </table>

            {{-- SIN RESULTADOS EN BÚSQUEDA --}}
            <div id="sin-resultados" class="hidden py-16 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron permisos</p>
                <p class="text-gray-400 mt-2">Intenta con otros términos de búsqueda</p>
            </div>
        </div>

        @else
        <div class="py-20 text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-green-400 text-4xl"></i>
            </div>
            <p class="text-gray-400 text-lg">No hay permisos disponibles</p>
            <a href="{{ route('permisos.create') }}" class="inline-flex items-center gap-2 text-green-600 text-sm mt-3 hover:underline">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Crear el primero
            </a>
        </div>
        @endif

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtro = document.getElementById('filtro');
    const filas = document.querySelectorAll('.fila-permiso');
    const totalSpan = document.getElementById('totalVisibles');
    const sinResultados = document.getElementById('sin-resultados');

    if (filtro) {
        filtro.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            let visibles = 0;

            filas.forEach(fila => {
                const coincide = !texto || 
                    (fila.dataset.nombre || '').includes(texto) || 
                    (fila.dataset.vista || '').includes(texto);
                fila.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });

            if (totalSpan) {
                // Contar grupos únicos visibles
                const gruposVisibles = new Set();
                filas.forEach(fila => {
                    if (fila.style.display !== 'none') {
                        const nombre = fila.dataset.nombre;
                        if (nombre) gruposVisibles.add(nombre);
                    }
                });
                totalSpan.textContent = gruposVisibles.size;
            }
            
            if (sinResultados) {
                sinResultados.classList.toggle('hidden', visibles > 0);
            }
        });
    }
});

async function eliminarPermiso(id) {
    if (!confirm('¿Seguro que quieres eliminar este permiso?')) return;

    try {
        const response = await fetch(`/permisos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            // Mostrar mensaje de éxito
            const successMsg = document.createElement('div');
            successMsg.className = 'fixed top-4 right-4 z-50 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg';
            successMsg.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> Permiso eliminado correctamente</div>';
            document.body.appendChild(successMsg);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Error al eliminar el permiso');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión al eliminar');
    }
}
</script>
</x-app-layout>