<x-app-layout>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fila-ministerio { animation: fadeIn 0.25s ease both; }
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
        <div class="bg-gradient-to-r from-green-700 px-6 py-5">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

                {{-- Título --}}
                <div>
                    <h2 class="text-white text-2xl font-bold">Ministerios</h2>
                    <p class="text-blue-100 text-sm mt-1">Gestión de ministerios del sistema</p>
                </div>

                {{-- Botones + Buscador --}}
                <div class="flex flex-col lg:flex-row gap-3 lg:items-center w-full lg:w-auto">

                    {{-- Botones --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('perfil.index') }}"
                            class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            ← Volver
                        </a>
                        <a href="{{ route('ministerio.create') }}"
                            class="inline-flex items-center gap-2 bg-white text-blue-700 hover:bg-blue-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuevo ministerio
                        </a>
                    </div>

                    {{-- Buscador --}}
                    <div class="relative w-full lg:w-72">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300 pointer-events-none"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input id="filtroMinisterios" type="text"
                            placeholder="Buscar por nombre..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-blue-400 bg-white shadow-sm text-sm placeholder-gray-400 outline-none focus:border-blue-300 focus:ring-1 focus:ring-blue-300 transition-all">
                    </div>

                </div>
            </div>
        </div>

        {{-- TABLA --}}
        @if(count($ministerios) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100" id="tablaMinisterios">
                    @foreach($ministerios as $loop_index => $m)
                        <tr class="fila-ministerio hover:bg-blue-50 transition-colors"
                            data-nombre="{{ strtolower($m['nombreMinisterio'] ?? '') }}"
                            style="animation-delay: {{ $loop->index * 30 }}ms">

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $m['idMinisterio'] ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($m['nombreMinisterio'] ?? 'M', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $m['nombreMinisterio'] ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $m['descripcion'] ?? '—' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('ministerio.edit', $m['idMinisterio'] ?? '') }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                                        Editar
                                    </a>
                                    <form method="POST"
                                        action="{{ route('ministerio.destroy', $m['idMinisterio'] ?? '') }}"
                                        class="inline"
                                        onsubmit="return confirm('¿Eliminar este ministerio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- SIN RESULTADOS --}}
            <div id="sin-resultados" class="hidden py-16 text-center text-gray-400 text-sm">
                Sin resultados para tu búsqueda
            </div>

        </div>

        @else
        <div class="py-20 text-center">
            <p class="text-gray-400 text-lg">No hay ministerios registrados</p>
            <a href="{{ route('ministerio.create') }}" class="text-blue-600 text-sm mt-2 inline-block hover:underline">
                Crear el primero
            </a>
        </div>
        @endif

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('filtroMinisterios').addEventListener('input', function () {
        const texto = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('.fila-ministerio');
        let visibles = 0;

        filas.forEach(fila => {
            const coincide = !texto || fila.dataset.nombre.includes(texto);
            fila.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        document.getElementById('sin-resultados')
            ?.classList.toggle('hidden', visibles > 0);
    });
});
</script>

</x-app-layout>