<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Asignaciones</h2>
                    <p class="text-green-100 text-sm">Listado de asignaciones de usuarios</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('perfil.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <a href="{{ route('asignaciones.create') }}" 
                       class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 transition">
                        <i class="fas fa-plus mr-2"></i> Nueva Asignación
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

            <!-- SECCIÓN DE BÚSQUEDA -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Búsqueda por texto -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i> Buscar asignación
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="filtroAsignaciones"
                                       placeholder="Nombre, correo, rol, ministerio o cargo..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-end justify-end space-x-3 md:col-span-2">
                            <!-- Dropdown Gestión -->
                            <div class="relative" id="dropdownWrapper">
                                <button id="btnGestion" onclick="toggleDropdown()"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center gap-2">
                                    <i class="fas fa-cog mr-1"></i> Gestión
                                    <svg id="chevron" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50">
                                    <a href="{{ route('rol.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-user-shield text-green-700 w-4"></i> Roles
                                    </a>
                                    <a href="{{ route('ministerio.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors border-t border-gray-100">
                                        <i class="fas fa-church text-green-700 w-4"></i> Ministerios
                                    </a>
                                    <a href="{{ route('cargo.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors border-t border-gray-100">
                                        <i class="fas fa-briefcase text-green-700 w-4"></i> Cargos
                                    </a>
                                </div>
                            </div>
                            
                            <button id="limpiarFiltros" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-2"></i> Limpiar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados de búsqueda -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-list-alt mr-1"></i>
                    Mostrando <span id="totalVisibles" class="font-semibold">{{ count($asignaciones) }}</span> asignaciones
                </div>
            </div>

            <!-- Tabla de asignaciones -->
            <div class="overflow-x-auto">
                @if(empty($asignaciones) || count($asignaciones) === 0)
                    <div class="text-center py-12">
                        <i class="fas fa-tasks text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay asignaciones registradas</p>
                        <a href="{{ route('asignaciones.create') }}" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Crear primera asignación
                        </a>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1"></i> ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1 text-green-500"></i> Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-envelope mr-1 text-green-500"></i> Correo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-shield mr-1 text-purple-500"></i> Rol
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-church mr-1 text-green-500"></i> Ministerio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-briefcase mr-1 text-orange-500"></i> Cargo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tablaAsignaciones">
                            @foreach($asignaciones as $asig)
                                <tr class="hover:bg-gray-50 transition fila-asignacion"
                                    data-nombre="{{ strtolower($asig['usuarioNombre'] ?? '') }}"
                                    data-correo="{{ strtolower($asig['usuarioEmail'] ?? '') }}"
                                    data-rol="{{ strtolower($asig['rolNombre'] ?? '') }}"
                                    data-ministerio="{{ strtolower($asig['ministerioNombre'] ?? '') }}"
                                    data-cargo="{{ strtolower($asig['cargoNombre'] ?? '') }}">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['idAsignacion'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['usuarioNombre'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['usuarioEmail'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-user-shield mr-1"></i> {{ $asig['rolNombre'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-church mr-1"></i> {{ $asig['ministerioNombre'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-briefcase mr-1"></i> {{ $asig['cargoNombre'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('asignaciones.edit', $asig['idAsignacion']) }}" 
                                           class="text-green-600 hover:text-green-900" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <button onclick="eliminarAsignacion({{ $asig['idAsignacion'] }})"
                                                class="text-red-600 hover:text-red-900" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Sin resultados -->
                    <div id="sin-resultados" class="hidden py-16 text-center">
                        <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No se encontraron asignaciones</p>
                        <p class="text-gray-400 mt-2">Intenta con otros términos de búsqueda</p>
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
                        ¿Estás seguro de eliminar esta asignación? Esta acción no se puede deshacer.
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
        // Dropdown Gestión
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            const chevron = document.getElementById('chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('dropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const menu = document.getElementById('dropdownMenu');
                const chevron = document.getElementById('chevron');
                if (menu) menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });

        // Filtros de búsqueda
        const searchInput = document.getElementById('filtroAsignaciones');
        const limpiarBtn = document.getElementById('limpiarFiltros');
        const filas = document.querySelectorAll('.fila-asignacion');
        const totalSpan = document.getElementById('totalVisibles');
        const sinResultadosDiv = document.getElementById('sin-resultados');

        function aplicarFiltros() {
            const texto = searchInput.value.toLowerCase().trim();
            let visibles = 0;

            filas.forEach(fila => {
                const coincide = !texto || 
                    (fila.dataset.nombre || '').includes(texto) ||
                    (fila.dataset.correo || '').includes(texto) ||
                    (fila.dataset.rol || '').includes(texto) ||
                    (fila.dataset.ministerio || '').includes(texto) ||
                    (fila.dataset.cargo || '').includes(texto);

                fila.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });

            if (totalSpan) totalSpan.textContent = visibles;
            
            if (sinResultadosDiv) {
                if (visibles === 0 && filas.length > 0) {
                    sinResultadosDiv.classList.remove('hidden');
                } else {
                    sinResultadosDiv.classList.add('hidden');
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', aplicarFiltros);
        }
        
        if (limpiarBtn) {
            limpiarBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                aplicarFiltros();
            });
        }

        // Eliminar asignación con modal
        let currentDeleteId = null;

        function eliminarAsignacion(idAsignacion) {
            currentDeleteId = idAsignacion;
            const modal = document.getElementById('deleteModal');
            if (modal) modal.classList.remove('hidden');
        }

        // Modal de confirmación
        const modal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', async function() {
                if (!currentDeleteId) return;
                
                try {
                    const response = await fetch(`http://127.0.0.1:5431/api/asignaciones/${currentDeleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        // Mostrar mensaje de éxito
                        const successMsg = document.createElement('div');
                        successMsg.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded fixed top-4 right-4 z-50';
                        successMsg.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> Asignación eliminada correctamente</div>';
                        document.body.appendChild(successMsg);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        alert('Error al eliminar la asignación');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de conexión al eliminar');
                }
                
                if (modal) modal.classList.add('hidden');
                currentDeleteId = null;
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                if (modal) modal.classList.add('hidden');
                currentDeleteId = null;
            });
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    currentDeleteId = null;
                }
            });
        }
    </script>
</x-app-layout>