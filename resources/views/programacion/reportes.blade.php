<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Reportes de Programaciones</h2>
                    <p class="text-blue-100 text-sm">Genera reportes en diferentes formatos</p>
                </div>
                <a href="{{ route('programacion.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <div class="p-6">
                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class="fas fa-calendar-alt text-blue-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-blue-600">{{ $total }}</p>
                        <p class="text-gray-600">Total Programaciones</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-green-600">{{ $confirmados }}</p>
                        <p class="text-gray-600">Confirmados</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-4 text-center">
                        <i class="fas fa-clock text-yellow-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-yellow-600">{{ $pendientes }}</p>
                        <p class="text-gray-600">Pendientes</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <i class="fas fa-exchange-alt text-gray-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-600">{{ $reemplazados }}</p>
                        <p class="text-gray-600">Reemplazados</p>
                    </div>
                </div>

                <!-- Botones de exportación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('programacion.reporte.excel') }}" class="flex items-center justify-center p-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">
                        <i class="fas fa-file-excel text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">Excel</p>
                            <p class="text-sm">Descargar en formato Excel</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('programacion.reporte.pdf') }}" class="flex items-center justify-center p-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">PDF</p>
                            <p class="text-sm">Descargar en formato PDF</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('programacion.reporte.csv') }}" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        <i class="fas fa-file-csv text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">CSV</p>
                            <p class="text-sm">Descargar en formato CSV</p>
                        </div>
                    </a>
                </div>

                <div class="mt-8 text-center text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i> Reporte generado el: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>