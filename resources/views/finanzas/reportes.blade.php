<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Reportes Financieros</h2>
                    <p class="text-green-100 text-sm">Genera reportes en diferentes formatos</p>
                </div>
                <a href="{{ route('finanzas.index') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <div class="p-6">
                <!-- Filtros de fecha -->
                <form method="GET" action="{{ route('finanzas.reporte') }}" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                <i class="fas fa-filter mr-2"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <i class="fas fa-money-bill-wave text-green-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($totalIngresos, 0, ',', '.') }}</p>
                        <p class="text-gray-600">Total Ingresos</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 text-center">
                        <i class="fas fa-credit-card text-red-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-red-600">${{ number_format($totalEgresos, 0, ',', '.') }}</p>
                        <p class="text-gray-600">Total Egresos</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class="fas fa-chart-line text-blue-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($balance, 0, ',', '.') }}</p>
                        <p class="text-gray-600">Balance General</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-4 text-center">
                        <i class="fas fa-folder-open text-yellow-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-yellow-600">{{ $movimientos->count() }}</p>
                        <p class="text-gray-600">Total Movimientos</p>
                    </div>
                </div>

                <!-- Botones de exportación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('finanzas.reporte.excel', request()->all()) }}" class="flex items-center justify-center p-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">
                        <i class="fas fa-file-excel text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">Excel</p>
                            <p class="text-sm">Descargar en formato Excel</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('finanzas.reporte.pdf', request()->all()) }}" class="flex items-center justify-center p-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">PDF</p>
                            <p class="text-sm">Descargar en formato PDF</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('finanzas.reporte.csv', request()->all()) }}" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        <i class="fas fa-file-csv text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">CSV</p>
                            <p class="text-sm">Descargar en formato CSV</p>
                        </div>
                    </a>
                </div>

                <!-- Tabla de movimientos para vista previa -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-list mr-2"></i> Vista previa de movimientos
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($movimientos->take(10) as $movimiento)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">{{ $movimiento->id_movimiento }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $movimiento->fecha }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $movimiento->categoria->nombre_categoria ?? 'Sin categoría' }}</td>
                                    <td class="px-4 py-3">
                                        @if($movimiento->categoria->tipo_finanza == 'Ingreso')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-up mr-1"></i> Ingreso
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-down mr-1"></i> Egreso
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold {{ $movimiento->categoria->tipo_finanza == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($movimiento->monto, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ Str::limit($movimiento->descripcion ?? 'Sin descripción', 50) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>No hay movimientos registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($movimientos->count() > 10)
                            <div class="text-center text-sm text-gray-500 mt-4">
                                <i class="fas fa-info-circle mr-1"></i> Mostrando 10 de {{ $movimientos->count() }} movimientos. El reporte incluirá todos los movimientos.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 text-center text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i> Reporte generado el: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>