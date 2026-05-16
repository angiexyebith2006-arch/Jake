
@php
    $permisos = session('permisos_jake', []);
@endphp

<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
<!-- Header - Actualiza esta sección -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-white">Usuarios</h2>
        <p class="text-blue-100 text-sm">Gestión de usuarios del sistema</p>
    </div>
    <div class="flex space-x-3">
        <!-- Botón de Reportes (NUEVO) -->
        <a href="{{ route('perfil.reportes') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition">
            <i class="fas fa-chart-bar mr-2"></i> Reportes
        </a>
        @if(in_array('editar', $permisos['usuarios'] ?? []))
        <a href="{{ route('permisos.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
            <i class="fas fa-shield-alt mr-2"></i> Permisos
        </a>
        <a href="{{ route('asignaciones.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
            <i class="fas fa-sliders-h mr-2"></i> Asignaciones
        </a>
        @endif
        @if(in_array('crear', $permisos['usuarios'] ?? []))
        <a href="{{ route('perfil.create') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </a>
        @endif
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
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Búsqueda por texto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i> Buscar usuario
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="filtroUsuarios"
                                       placeholder="Nombre, correo o teléfono..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Filtro por estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-filter mr-1"></i> Estado
                            </label>
                            <select id="filtroEstado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-end justify-end space-x-3">
                            <button id="limpiarFiltros" 
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-2"></i> Limpiar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados de búsqueda -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-users mr-1"></i>
                    Mostrando <span id="totalVisibles" class="font-semibold">{{ $usuarios->count() }}</span> usuarios
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="overflow-x-auto">
                @if($usuarios->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-user-slash text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay usuarios registrados</p>
                        <a href="{{ route('perfil.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Crear primer usuario
                        </a>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tablaUsuarios">
                            @foreach($usuarios as $usuario)
                                <tr class="hover:bg-gray-50 transition fila-usuario"
                                    data-nombre="{{ strtolower($usuario->nombre) }}"
                                    data-correo="{{ strtolower($usuario->correo) }}"
                                    data-telefono="{{ strtolower($usuario->telefono ?? '') }}"
                                    data-estado="{{ $usuario->activo ? 'activo' : 'inactivo' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $usuario->id_usuario }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $usuario->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $usuario->correo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $usuario->telefono ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($usuario->activo) bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            <i class="fas 
                                                @if($usuario->activo) fa-check-circle
                                                @else fa-ban @endif mr-1"></i>
                                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                         @if(in_array('ver', $permisos['usuarios'] ?? []))
                                        <a href="{{ route('perfil.show', $usuario->id_usuario) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Ver">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        @endif
                                        @if(in_array('editar', $permisos['usuarios'] ?? []))
                                        <a href="{{ route('perfil.edit', $usuario->id_usuario) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        @endif
                                        <form action="{{ route('perfil.destroy', $usuario->id_usuario) }}" 
                                              method="POST" 
                                              class="inline delete-form"
                                              data-id="{{ $usuario->id_usuario }}">
                                            @csrf
                                            @method('DELETE')
                                             @if(in_array('editar', $permisos['usuarios'] ?? []))
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-900 delete-btn"
                                                    data-id="{{ $usuario->id_usuario }}"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                             @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Sin resultados -->
                    <div id="sin-resultados" class="hidden py-16 text-center">
                        <i class="fas fa-user-slash text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No se encontraron usuarios</p>
                        <p class="text-gray-400 mt-2">Intenta con otros filtros de búsqueda</p>
                    </div>
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
                        ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.
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
        // Filtros combinados (búsqueda + estado)
        const searchInput = document.getElementById('filtroUsuarios');
        const estadoSelect = document.getElementById('filtroEstado');
        const limpiarBtn = document.getElementById('limpiarFiltros');
        const filas = document.querySelectorAll('.fila-usuario');
        const totalSpan = document.getElementById('totalVisibles');
        const sinResultadosDiv = document.getElementById('sin-resultados');
        const tablaBody = document.getElementById('tablaUsuarios');

        function aplicarFiltros() {
            const texto = searchInput.value.toLowerCase().trim();
            const estado = estadoSelect.value;
            let visibles = 0;

            filas.forEach(fila => {
                const coincideTexto = !texto || 
                    fila.dataset.nombre.includes(texto) ||
                    fila.dataset.correo.includes(texto) ||
                    fila.dataset.telefono.includes(texto);
                
                const coincideEstado = !estado || fila.dataset.estado === estado;
                
                const esVisible = coincideTexto && coincideEstado;
                fila.style.display = esVisible ? '' : 'none';
                if (esVisible) visibles++;
            });

            totalSpan.textContent = visibles;
            
            if (sinResultadosDiv && tablaBody) {
                if (visibles === 0 && filas.length > 0) {
                    sinResultadosDiv.classList.remove('hidden');
                } else {
                    sinResultadosDiv.classList.add('hidden');
                }
            }
        }

        searchInput.addEventListener('input', aplicarFiltros);
        estadoSelect.addEventListener('change', aplicarFiltros);
        
        limpiarBtn.addEventListener('click', function() {
            searchInput.value = '';
            estadoSelect.value = '';
            aplicarFiltros();
        });

        // Modal de confirmación de eliminación
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
    </script>
</x-app-layout>