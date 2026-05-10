<x-app-layout>
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fila-permiso { animation: fadeIn 0.25s ease both; }
</style>

<main class="p-6 max-w-6xl mx-auto">

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl text-sm font-medium">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded-xl text-sm font-medium">
            <span>✕</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-white text-2xl font-bold">Permisos</h2>
                <p class="text-green-100 text-sm mt-1">Control de acceso por usuario y módulo</p>
            </div>
            <a href="{{ route('permisos.create') }}"
                class="inline-flex items-center gap-2 bg-white text-green-700 hover:bg-green-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nuevo permiso
            </a>
        </div>

        {{-- BUSCADOR --}}
        <div class="px-6 py-4 border-b bg-gray-50">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input id="filtro" type="text" placeholder="Buscar por usuario o vista..."
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 bg-white shadow-sm text-sm placeholder-gray-400 outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all">
            </div>
        </div>

        {{-- TABLA --}}
        @if(count($agrupados) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-56">
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-40">
                            Vista
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Acciones
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider w-40">
                            Opciones
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
                                    <td class="px-6 py-4 align-middle" rowspan="{{ count($grupo['vistas']) }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                                {{ strtoupper(substr($grupo['nombre'], 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $grupo['nombre'] }}</p>
                                                <p class="text-xs text-gray-400">#{{ $grupo['idAsignacion'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                {{-- VISTA --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ ucfirst($vistaNombre) }}
                                    </span>
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
                                            <span class="text-xs font-medium px-3 py-1 rounded-full {{ $badge }}">
                                                {{ ucfirst($accion) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- BOTONES --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('permisos.edit', $info['id']) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                                            Editar
                                        </a>
                                        <button onclick="eliminarPermiso({{ $info['id'] }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                                            Eliminar
                                        </button>
                                    </div>
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
            <div id="sin-resultados" class="hidden py-16 text-center text-gray-400 text-sm">
                Sin resultados para tu búsqueda
            </div>
        </div>

        @else
        {{-- VACÍO --}}
        <div class="py-20 text-center">
            <p class="text-gray-400 text-lg">No hay permisos disponibles</p>
            <a href="{{ route('permisos.create') }}"
                class="text-green-600 text-sm mt-2 inline-block hover:underline">
                Crear el primero
            </a>
        </div>
        @endif

    </div>
</main>

<script>
document.getElementById('filtro').addEventListener('input', function () {
    const texto = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('.fila-permiso');
    let visibles = 0;

    filas.forEach(fila => {
        const coincide = !texto || fila.dataset.nombre.includes(texto) || fila.dataset.vista.includes(texto);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visibles++;
    });

    document.getElementById('sin-resultados')?.classList.toggle('hidden', visibles > 0);
});

async function eliminarPermiso(id) {
    if (!confirm('¿Seguro que quieres eliminar este permiso?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/permisos/${id}`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
</x-app-layout>