<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                    
                    <!-- Título -->
                    <div>
                        <h2 class="text-white text-2xl font-bold">Roles</h2>
                        <p class="text-green-100 text-sm mt-1">Permisos y niveles de acceso del sistema</p>
                    </div>

                    <!-- Botones + Buscador -->
                    <div class="flex flex-col lg:flex-row gap-3 lg:items-center w-full lg:w-auto">
                        
                        <!-- Botones -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('asignaciones.index') }}" 
                               class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Volver
                            </a>
                            <a href="{{ route('rol.create') }}" 
                               class="inline-flex items-center gap-2 bg-white text-green-700 hover:bg-green-50 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Nuevo Rol
                            </a>
                        </div>

                        <!-- Buscador -->
                        <div class="relative w-full lg:w-72">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-green-300 pointer-events-none"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                            </svg>
                            <input id="filtroRoles" 
                                   type="text"
                                   placeholder="Buscar por nombre..."
                                   class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-green-400 bg-white shadow-sm text-sm placeholder-gray-400 outline-none focus:border-green-300 focus:ring-1 focus:ring-green-300 transition-all">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="mx-6 mt-4 flex items-center gap-3 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl text-sm font-medium">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 flex items-center gap-3 bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded-xl text-sm font-medium">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Resultados -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-tags mr-1"></i>
                    Mostrando <span id="totalVisibles" class="font-semibold">{{ count($roles) }}</span> roles
                </div>
            </div>

            <!-- Tabla de roles -->
            @if(empty($roles) || count($roles) === 0)
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-100 to-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-green-400 text-4xl"></i>
                    </div>
                    <p class="text-gray-400 text-lg">No hay roles registrados</p>
                    <a href="{{ route('rol.create') }}" class="inline-flex items-center gap-2 text-green-600 text-sm mt-3 hover:underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear el primero
                    </a>
                </div>
            @else
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
                        <tbody class="bg-white divide-y divide-gray-100" id="tablaRoles">
                            @foreach($roles as $index => $r)
                                <tr class="fila-rol hover:bg-green-50 transition-colors"
                                    data-nombre="{{ strtolower($r['nombre'] ?? '') }}"
                                    style="animation-delay: {{ $index * 30 }}ms">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $r['id'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-green-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                                {{ strtoupper(substr($r['nombre'] ?? 'R', 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $r['nombre'] ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                        {{ $r['descripcion'] ?? 'Sin descripción' }}
                                    </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
    <a href="{{ route('rol.edit', $r['id']) }}" 
       class="text-green-600 hover:text-green-900" title="Editar">
        <i class="fas fa-edit"></i> Editar
    </a>
    <button type="button" 
            class="delete-btn text-red-600 hover:text-red-900" 
            title="Eliminar"
            data-id="{{ $r['id'] }}"
            data-nombre="{{ $r['nombre'] }}">
        <i class="fas fa-trash-alt"></i> Eliminar
    </button>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Sin resultados -->
                    <div id="sin-resultados" class="hidden py-16 text-center text-gray-400 text-sm">
                        Sin resultados para tu búsqueda
                    </div>
                </div>
            @endif
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
                        ¿Estás seguro de eliminar el rol <strong id="rolNombre"></strong>? Esta acción no se puede deshacer.
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

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fila-rol {
            animation: fadeIn 0.25s ease both;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroRoles');
            const filas = document.querySelectorAll('.fila-rol');
            const totalSpan = document.getElementById('totalVisibles');
            const sinResultadosDiv = document.getElementById('sin-resultados');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const texto = this.value.toLowerCase().trim();
                    let visibles = 0;

                    filas.forEach(fila => {
                        const coincide = !texto || (fila.dataset.nombre || '').includes(texto);
                        fila.style.display = coincide ? '' : 'none';
                        if (coincide) visibles++;
                    });

                    if (totalSpan) totalSpan.textContent = visibles;
                    
                    if (sinResultadosDiv) {
                        sinResultadosDiv.classList.toggle('hidden', visibles > 0);
                    }
                });
            }

            // Modal de confirmación de eliminación
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const cancelBtn = document.getElementById('cancelDeleteBtn');
            const rolNombreSpan = document.getElementById('rolNombre');
            let currentForm = null;
            let currentDeleteUrl = null;
            
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nombre = this.getAttribute('data-nombre') || 'este rol';
                    currentDeleteUrl = `/rol/${id}`;
                    if (rolNombreSpan) rolNombreSpan.textContent = nombre;
                    if (modal) modal.classList.remove('hidden');
                });
            });
            
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (currentDeleteUrl) {
                        // Crear formulario para eliminar
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = currentDeleteUrl;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                    if (modal) modal.classList.add('hidden');
                    currentDeleteUrl = null;
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (modal) modal.classList.add('hidden');
                    currentDeleteUrl = null;
                });
            }
            
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        currentDeleteUrl = null;
                    }
                });
            }
        });
    </script>
</x-app-layout>