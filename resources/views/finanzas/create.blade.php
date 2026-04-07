<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Movimiento Financiero</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Main Content -->
    <main class="p-6 max-w-4xl mx-auto">
        <!-- Alertas de error -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                        <i class="fas fa-plus text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Nuevo Movimiento Financiero</h2>
                        <p class="text-blue-100 text-sm">Registra un nuevo movimiento financiero</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('finanzas.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        <!-- Categoría -->
                        <div>
                            <label for="id_categoria" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-green-500 mr-2"></i>Categoría *
                            </label>
                            <select id="id_categoria" name="id_categoria" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                    required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                        {{ $categoria->nombre_categoria }} ({{ $categoria->tipo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Monto -->
                        <div>
                            <label for="monto" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign text-yellow-500 mr-2"></i>Monto *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" id="monto" name="monto" 
                                       step="0.01" min="0.01"
                                       value="{{ old('monto') }}"
                                       class="w-full border border-gray-300 rounded-xl pl-8 pr-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm" 
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Fecha *
                            </label>
                            <input type="date" id="fecha" name="fecha" 
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                   required>
                        </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-file-alt text-indigo-500 mr-2"></i>Descripción *
                        </label>
                        <textarea id="descripcion" name="descripcion" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                  placeholder="Ingrese una descripción detallada del movimiento..."
                                  required>{{ old('descripcion') }}</textarea>
                    </div>

                    <!-- Preview del movimiento -->
                    <div id="movementPreview" class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200 hidden">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-eye mr-2 text-blue-500"></i>Vista Previa
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3 text-green-600">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Categoría</p>
                                    <p id="previewCategoria" class="font-semibold text-gray-800">-</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-3 text-yellow-600">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Monto</p>
                                    <p id="previewMonto" class="font-semibold text-gray-800">-</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3 text-purple-600">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Fecha</p>
                                    <p id="previewFecha" class="font-semibold text-gray-800">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                        <a href="{{ route('finanzas.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Cancelar
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button" id="previewBtn"
                                    class="bg-blue-200 hover:bg-blue-300 text-blue-800 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-eye mr-2"></i>Vista Previa
                            </button>
                            
                            <button type="submit" 
                                    class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold inline-flex items-center">
                                <i class="fas fa-save mr-2"></i>Guardar Movimiento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoInput = document.getElementById('monto');
            const categoriaSelect = document.getElementById('id_categoria');
            const fechaInput = document.getElementById('fecha');
            const previewBtn = document.getElementById('previewBtn');
            const previewContainer = document.getElementById('movementPreview');
            
            // Formatear monto
            montoInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
            
            // Vista previa
            previewBtn.addEventListener('click', function() {
                const categoriaText = categoriaSelect.options[categoriaSelect.selectedIndex]?.text || '-';
                const montoText = montoInput.value ? '$' + parseFloat(montoInput.value).toFixed(2) : '-';
                const fechaText = fechaInput.value ? new Date(fechaInput.value).toLocaleDateString('es-ES') : '-';
                
                document.getElementById('previewCategoria').textContent = categoriaText;
                document.getElementById('previewMonto').textContent = montoText;
                document.getElementById('previewFecha').textContent = fechaText;
                
                previewContainer.classList.remove('hidden');
            });
            
            // Actualizar vista previa al cambiar campos
            [categoriaSelect, montoInput, fechaInput].forEach(element => {
                element.addEventListener('change', function() {
                    if (!previewContainer.classList.contains('hidden')) {
                        previewBtn.click();
                    }
                });
            });
            
            // Establecer fecha de hoy por defecto si está vacía
            if (!fechaInput.value) {
                const today = new Date().toISOString().split('T')[0];
                fechaInput.value = today;
            }
        });
    </script>
</body>
</html>
</x-app-layout>