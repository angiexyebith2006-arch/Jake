<x-app-layout>
@php
    $btnClass = "inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors";

    $badges = [
        'crear'     => ['bg-blue-100 text-blue-700', 'fa-plus'],
        'editar'    => ['bg-amber-100 text-amber-700', 'fa-edit'],
        'eliminar'  => ['bg-red-100 text-red-700', 'fa-trash-alt'],
        'ver'       => ['bg-purple-100 text-purple-700', 'fa-eye'],
        'responder' => ['bg-teal-100 text-teal-700', 'fa-reply'],
    ];
@endphp

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fila-permiso { animation: fadeIn .25s ease both; }
</style>

<main class="p-6 max-w-7xl mx-auto">

    @foreach (['success' => 'green', 'error' => 'red'] as $msg => $color)
        @if(session($msg))
            <div class="mb-4 flex items-center gap-3 bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 px-5 py-3 rounded-xl text-sm font-medium">
                <i class="fas fa-{{ $msg == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i>
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-5">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h2 class="text-white text-2xl font-bold">Permisos</h2>
                    <p class="text-green-100 text-sm mt-1">Control de acceso por usuario y módulo</p>
                </div>
                <div class="flex flex-col lg:flex-row gap-3 lg:items-center w-full lg:w-auto">
                    <div class="flex flex-wrap gap-2 items-center">
                        <a href="{{ route('perfil.index') }}" class="{{ $btnClass }} bg-white text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <div class="relative" id="dropdownWrapper">
                            <button onclick="toggleDropdown()" class="{{ $btnClass }} bg-white text-green-700 hover:bg-green-50">
                                <i class="fas fa-cog"></i> Gestión
                                <i id="chevron" class="fas fa-chevron-down text-xs transition-transform"></i>
                            </button>
                            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                                <a href="{{ route('acciones.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-50">
                                    <i class="fas fa-bolt text-amber-500"></i> Acciones
                                </a>
                                <a href="{{ route('vistas.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-50 border-t border-gray-100">
                                    <i class="fas fa-eye text-purple-500"></i> Vistas
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('permisos.create') }}" class="{{ $btnClass }} bg-white text-green-700 hover:bg-green-50">
                            <i class="fas fa-plus"></i> Nuevo permiso
                        </a>
                    </div>
                    <div class="relative w-full lg:w-80">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-green-300"></i>
                        <input id="filtro" type="text" placeholder="Buscar por usuario o vista..."
                               class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-green-400 bg-white shadow-sm text-sm outline-none focus:border-green-300 focus:ring-1 focus:ring-green-300">
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 text-sm text-gray-600">
            <i class="fas fa-lock mr-1"></i>
            Mostrando <span id="totalVisibles" class="font-semibold">{{ count($agrupados) }}</span> permisos
        </div>

        @if(count($agrupados))
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach([['Usuario','fa-user'],['Vista','fa-eye'],['Acciones','fa-cogs'],['Opciones','fa-cog']] as [$titulo,$icon])
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <i class="fas {{ $icon }} mr-1 text-green-500"></i> {{ $titulo }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100" id="tabla-body">
                    @foreach($agrupados as $grupo)
                        @foreach($grupo['vistas'] as $vistaNombre => $info)
                            <tr class="fila-permiso hover:bg-green-50 transition-colors"
                                data-nombre="{{ strtolower($grupo['nombre']) }}"
                                data-vista="{{ strtolower($vistaNombre) }}"
                                id="fila-{{ $info['id'] }}">

                                {{-- ✅ Usuario visible en TODAS las filas, sin rowspan, sin ocultar --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($grupo['nombre'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $grupo['nombre'] }}</p>
                                            <p class="text-xs text-gray-400">ID: {{ $grupo['idAsignacion'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                    {{ ucfirst($vistaNombre) }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($info['acciones'] as $accion)
                                            @php [$color,$icon] = $badges[$accion] ?? ['bg-gray-100 text-gray-600','fa-circle']; @endphp
                                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $color }}">
                                                <i class="fas {{ $icon }} mr-1"></i>{{ ucfirst($accion) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('permisos.edit', $info['id']) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button onclick="eliminarPermiso({{ $info['id'] }}, this)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        {{-- Separador visual entre grupos --}}
                        @unless($loop->last)
                            <tr><td colspan="4" class="bg-gray-50 py-1"></td></tr>
                        @endunless
                    @endforeach
                </tbody>
            </table>

            <div id="sin-resultados" class="hidden py-16 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron permisos</p>
            </div>
        </div>

        @else
        <div class="py-20 text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-green-400 text-4xl"></i>
            </div>
            <p class="text-gray-400 text-lg">No hay permisos disponibles</p>
            <a href="{{ route('permisos.create') }}" class="inline-flex items-center gap-2 text-green-600 text-sm mt-3 hover:underline">
                <i class="fas fa-plus"></i> Crear el primero
            </a>
        </div>
        @endif

    </div>
</main>

<script>
    const $ = id => document.getElementById(id);

    function toggleDropdown() {
        $('dropdownMenu')?.classList.toggle('hidden');
        $('chevron')?.classList.toggle('rotate-180');
    }
    document.addEventListener('click', e => {
        if (!$('dropdownWrapper')?.contains(e.target)) {
            $('dropdownMenu')?.classList.add('hidden');
            $('chevron')?.classList.remove('rotate-180');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const filtro = $('filtro');
        const filas  = [...document.querySelectorAll('.fila-permiso')];

        filtro?.addEventListener('input', () => {
            const texto = filtro.value.toLowerCase().trim();
            let grupos = new Set();

            filas.forEach(fila => {
                const coincide = !texto ||
                    ['nombre','vista'].some(k => (fila.dataset[k]||'').includes(texto));
                fila.style.display = coincide ? '' : 'none';
                if (coincide) grupos.add(fila.dataset.nombre);
            });

            $('totalVisibles').textContent = grupos.size;
            $('sin-resultados')?.classList.toggle('hidden', grupos.size > 0);
        });
    });

    function eliminarPermiso(id, btn) {
        if (!confirm('¿Seguro que quieres eliminar este permiso?')) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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