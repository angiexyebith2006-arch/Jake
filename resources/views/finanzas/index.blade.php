<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Finanzas</h2>
                    <p class="text-blue-100 text-sm">Gestión de movimientos financieros</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('finanzas.reporte') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition">
                        <i class="fas fa-chart-bar mr-2"></i> Reportes
                    </a>
                    <a href="{{ route('categorias.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-tags mr-2"></i> Categorías
                    </a>
                    <a href="{{ route('finanzas.create') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-plus mr-2"></i> Nuevo Movimiento
                    </a>
                </div>
            </div>

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    <div class="flex items-center"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
                </div>
            @endif

            {{-- Cards resumen --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">
                        <i class="fas fa-arrow-down mr-1"></i> Ingresos
                    </p>
                    <p class="text-xl font-bold text-green-700">${{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-red-600 uppercase tracking-wide mb-1">
                        <i class="fas fa-arrow-up mr-1"></i> Egresos
                    </p>
                    <p class="text-xl font-bold text-red-700">${{ number_format($totalEgresos, 2) }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">
                        <i class="fas fa-balance-scale mr-1"></i> Balance
                    </p>
                    <p class="text-xl font-bold text-blue-700">${{ number_format($balance, 2) }}</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">
                        <i class="fas fa-exchange-alt mr-1"></i> Movimientos
                    </p>
                    <p class="text-xl font-bold text-purple-700">{{ $totalMovimientos }}</p>
                </div>
            </div>

            {{-- Búsqueda --}}
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1"></i> Buscar movimiento
                        </label>
                        <div class="relative">
                            <input type="text" id="filtroMovimientos"
                                   placeholder="Descripción, categoría, monto..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-1"></i> Tipo
                        </label>
                        <select id="filtroTipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los tipos</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>
                    <div class="flex items-end justify-end">
                        <button id="limpiarFiltros" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-times mr-2"></i> Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>

            {{-- Contador --}}
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-coins mr-1"></i>
                    Mostrando <span id="totalVisibles" class="font-semibold">{{ $movimientos->count() }}</span> movimientos
                </div>
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                @if($movimientos->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-coins text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay movimientos registrados</p>
                        <a href="{{ route('finanzas.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Crear movimiento
                        </a>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tablaMovimientos">
                            @foreach($movimientos as $mov)
                                @php $tipo = strtolower($mov->categoria->tipo_finanza ?? ''); @endphp
                                <tr class="hover:bg-gray-50 transition fila-movimiento"
                                    data-descripcion="{{ strtolower($mov->descripcion ?? '') }}"
                                    data-categoria="{{ strtolower($mov->categoria->nombre_categoria ?? '') }}"
                                    data-monto="{{ $mov->monto }}"
                                    data-tipo="{{ $tipo }}">

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $mov->categoria->nombre_categoria }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $tipo === 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas {{ $tipo === 'ingreso' ? 'fa-check-circle' : 'fa-ban' }} mr-1"></i>
                                            {{ ucfirst($tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold
                                        {{ $tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($mov->monto, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $mov->descripcion ?? 'Sin descripción' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="{{ route('finanzas.show', $mov->id_movimiento) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('finanzas.edit', $mov->id_movimiento) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('finanzas.destroy', $mov->id_movimiento) }}"
                                              method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-900 delete-btn">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="sin-resultados" class="hidden py-16 text-center">
                        <i class="fas fa-coins text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No se encontraron movimientos</p>
                        <p class="text-gray-400 mt-2">Intenta con otros filtros de búsqueda</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Modal eliminación --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar eliminación</h3>
                <p class="text-sm text-gray-500 mt-2 px-7">
                    ¿Estás seguro de eliminar este movimiento? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancelar</button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput   = document.getElementById('filtroMovimientos');
        const tipoSelect    = document.getElementById('filtroTipo');
        const limpiarBtn    = document.getElementById('limpiarFiltros');
        const filas         = document.querySelectorAll('.fila-movimiento');
        const totalSpan     = document.getElementById('totalVisibles');
        const sinResultados = document.getElementById('sin-resultados');

        function aplicarFiltros() {
            const texto = searchInput.value.toLowerCase().trim();
            const tipo  = tipoSelect.value;
            let visibles = 0;

            filas.forEach(fila => {
                const coincideTexto = !texto ||
                    fila.dataset.descripcion.includes(texto) ||
                    fila.dataset.categoria.includes(texto)  ||
                    fila.dataset.monto.includes(texto);

                const esVisible = coincideTexto && (!tipo || fila.dataset.tipo === tipo);
                fila.style.display = esVisible ? '' : 'none';
                if (esVisible) visibles++;
            });

            totalSpan.textContent = visibles;
            sinResultados.classList.toggle('hidden', !(visibles === 0 && filas.length > 0));
        }

        searchInput.addEventListener('input',  aplicarFiltros);
        tipoSelect.addEventListener('change',  aplicarFiltros);
        limpiarBtn.addEventListener('click', () => { searchInput.value = ''; tipoSelect.value = ''; aplicarFiltros(); });

        // Modal
        const modal      = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn  = document.getElementById('cancelDeleteBtn');
        let currentForm  = null;

        document.querySelectorAll('.delete-btn').forEach(btn =>
            btn.addEventListener('click', () => { currentForm = btn.closest('.delete-form'); modal.classList.remove('hidden'); })
        );
        confirmBtn.addEventListener('click', () => { currentForm?.submit(); modal.classList.add('hidden'); });
        cancelBtn.addEventListener('click',  () => modal.classList.add('hidden'));
        modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });
    </script>
</x-app-layout>