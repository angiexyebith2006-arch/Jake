<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Reportes de Usuarios</h2>
                    <p class="text-blue-100 text-sm">Genera reportes en diferentes formatos</p>
                </div>
                <a href="{{ route('perfil.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <div class="p-6">
                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class="fas fa-users text-blue-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-blue-600">{{ $total }}</p>
                        <p class="text-gray-600">Total Usuarios</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <i class="fas fa-user-check text-green-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-green-600">{{ $activos }}</p>
                        <p class="text-gray-600">Usuarios Activos</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 text-center">
                        <i class="fas fa-user-slash text-red-600 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-red-600">{{ $inactivos }}</p>
                        <p class="text-gray-600">Usuarios Inactivos</p>
                    </div>
                </div>

                <!-- Botones de exportación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('perfil.reporte.excel') }}" class="flex items-center justify-center p-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">
                        <i class="fas fa-file-excel text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">Excel</p>
                            <p class="text-sm">Descargar en formato Excel</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('perfil.reporte.pdf') }}" class="flex items-center justify-center p-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">PDF</p>
                            <p class="text-sm">Descargar en formato PDF</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('perfil.reporte.csv') }}" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        <i class="fas fa-file-csv text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold">CSV</p>
                            <p class="text-sm">Descargar en formato CSV</p>
                        </div>
                    </a>
                </div>

                <!-- Tabla de usuarios para vista previa -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-list mr-2"></i> Vista previa de usuarios
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usuarios->take(10) as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">{{ $usuario->id_usuario }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $usuario->nombre }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $usuario->correo }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $usuario->telefono ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $usuario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($usuarios->count() > 10)
                            <div class="text-center text-sm text-gray-500 mt-4">
                                <i class="fas fa-info-circle mr-1"></i> Mostrando 10 de {{ $usuarios->count() }} usuarios. El reporte incluirá todos los usuarios.
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