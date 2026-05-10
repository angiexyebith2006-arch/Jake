<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Programaciones</h2>
                    <p class="text-blue-100 text-sm">Gestión de programaciones</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('programacion.reportes') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                        <i class="fas fa-chart-bar mr-2"></i> Reportes
                    </a>
                    <a href="{{ route('actividades.index') }}" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-tasks mr-2"></i> Actividad
                    </a>
                    <a href="{{ route('programacion.create') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-plus mr-2"></i> Nueva Programación
                    </a>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Errores encontrados:</strong>
                    </div>
                    <ul class="list-disc list-inside ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SECCIÓN DE BÚSQUEDA -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('programacion.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Búsqueda por texto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i> Buscar
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Actividad o asignación..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Filtro por estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-filter mr-1"></i> Estado
                            </label>
                            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="reemplazado" {{ request('estado') == 'reemplazado' ? 'selected' : '' }}>Reemplazado</option>
                                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                <option value="reemplazo_solicitado" {{ request('estado') == 'reemplazo_solicitado' ? 'selected' : '' }}>Reemplazo Solicitado</option>
                            </select>
                        </div>

                        <!-- Filtro por fecha desde -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Desde
                            </label>
                            <input type="date" 
                                   name="fecha_desde" 
                                   value="{{ request('fecha_desde') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Filtro por fecha hasta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Hasta
                            </label>
                            <input type="date" 
                                   name="fecha_hasta" 
                                   value="{{ request('fecha_hasta') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3">
                        @if(request('search') || request('estado') || request('fecha_desde') || request('fecha_hasta'))
                            <a href="{{ route('programacion.index') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-2"></i> Limpiar filtros
                            </a>
                        @endif
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resultados de búsqueda -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-chart-line mr-1"></i>
                    Mostrando <span class="font-semibold">{{ $programaciones->count() }}</span> programaciones
                    @if(request('search') || request('estado') || request('fecha_desde') || request('fecha_hasta'))
                        <span class="text-gray-500">(filtrados)</span>
                    @endif
                </div>
                @if(request('search') || request('estado') || request('fecha_desde') || request('fecha_hasta'))
                    <div class="text-sm">
                        <a href="{{ route('programacion.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye mr-1"></i> Ver todos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tabla de programaciones -->
            <div class="overflow-x-auto">
                @if($programaciones->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No se encontraron programaciones</p>
                        @if(request('search') || request('estado') || request('fecha_desde') || request('fecha_hasta'))
                            <p class="text-gray-400 mt-2">Intenta con otros filtros de búsqueda</p>
                            <a href="{{ route('programacion.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-1"></i> Limpiar filtros
                            </a>
                        @else
                            <a href="{{ route('programacion.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i> Crear primera programación
                            </a>
                        @endif
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($programaciones as $prog)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prog->id_programacion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span title="ID: {{ $prog->id_actividad }}">{{ $prog->nombre_actividad }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span title="ID: {{ $prog->id_asignacion }}">{{ $prog->nombre_asignacion }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($prog->fecha)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($prog->estado == 'confirmado') bg-green-100 text-green-800
                                            @elseif($prog->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($prog->estado == 'reemplazo_solicitado') bg-orange-100 text-orange-800
                                            @elseif($prog->estado == 'reemplazado') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="fas 
                                                @if($prog->estado == 'confirmado') fa-check-circle
                                                @elseif($prog->estado == 'pendiente') fa-clock
                                                @elseif($prog->estado == 'reemplazo_solicitado') fa-exchange-alt
                                                @elseif($prog->estado == 'reemplazado') fa-user-friends
                                                @else fa-ban @endif mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $prog->estado)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="{{ route('programacion.edit', $prog->id_programacion) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('programacion.destroy', $prog->id_programacion) }}" 
                                              method="POST" 
                                              class="inline delete-form"
                                              data-id="{{ $prog->id_programacion }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-900 delete-btn"
                                                    data-id="{{ $prog->id_programacion }}"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>No hay programaciones que coincidan con los filtros</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar eliminación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        ¿Estás seguro de eliminar esta programación? Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="cancelDeleteBtn" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const cancelBtn = document.getElementById('cancelDeleteBtn');
            let currentForm = null;
            
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentForm = this.closest('.delete-form');
                    modal.classList.remove('hidden');
                });
            });
            
            confirmBtn.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
                modal.classList.add('hidden');
                currentForm = null;
            });
            
            cancelBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                currentForm = null;
            });
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    currentForm = null;
                }
            });
        });
    </script>
</x-app-layout>