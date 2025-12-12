<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Movimiento Financiero</title>
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
                        <i class="fas fa-edit text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Editar Movimiento</h2>
                        <p class="text-yellow-100 text-sm">Modifica los detalles del movimiento financiero</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('finanzas.update', $movimiento->id_movimiento) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Ministerio -->
                        <div>
                            <label for="id_ministerio" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-church text-purple-500 mr-2"></i>Ministerio
                            </label>
                            <select id="id_ministerio" name="id_ministerio" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                    required>
                                <option value="" disabled>Seleccione un ministerio</option>
                                @foreach($ministerios as $ministerio)
                                    <option value="{{ $ministerio->id_ministerio }}" {{ old('id_ministerio', $movimiento->id_ministerio) == $ministerio->id_ministerio ? 'selected' : '' }}>
                                        {{ $ministerio->nombre_ministerio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label for="id_categoria" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-green-500 mr-2"></i>Categoría
                            </label>
                            <select id="id_categoria" name="id_categoria" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                    required>
                                <option value="" disabled>Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $movimiento->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                        {{ $categoria->nombre_categoria }} ({{ $categoria->tipo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Monto -->
                        <div>
                            <label for="monto" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign text-yellow-500 mr-2"></i>Monto
                            </label>
                            <input type="number" id="monto" name="monto" 
                                   step="0.01" min="0.01"
                                   value="{{ old('monto', $movimiento->monto) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm" 
                                   placeholder="$0.00"
                                   required>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Fecha
                            </label>
                            <input type="date" id="fecha" name="fecha" 
                                   value="{{ old('fecha', $movimiento->fecha->format('Y-m-d')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                   required>
                        </div>

                        <!-- Registrado Por (Opcional) -->
                        <div>
                            <label for="registrado_por" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-gray-500 mr-2"></i>Registrado Por
                            </label>
                            <select id="registrado_por" name="registrado_por" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                                <option value="">Sin especificar</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}" {{ old('registrado_por', $movimiento->registrado_por) == $usuario->id_usuario ? 'selected' : '' }}>
                                        {{ $usuario->nombre }} ({{ $usuario->correo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div class="md:col-span-2">
                            <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-indigo-500 mr-2"></i>Descripción
                            </label>
                            <textarea id="descripcion" name="descripcion" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
                                      placeholder="Descripción del movimiento"
                                      required>{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                        </div>
                    </div>

                    <!-- Información del movimiento -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>Información del Movimiento
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3 text-blue-600">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">ID del Movimiento</p>
                                    <p class="font-semibold text-gray-800">#{{ $movimiento->id_movimiento }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $movimiento->categoria->tipo == 'Ingreso' ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center mr-3 {{ $movimiento->categoria->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $movimiento->categoria->tipo == 'Ingreso' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tipo de Movimiento</p>
                                    <p class="font-semibold {{ $movimiento->categoria->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $movimiento->categoria->tipo }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3 text-purple-600">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Fecha de Creación</p>
                                    <p class="font-semibold text-gray-800">{{ $movimiento->fecha->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-3 text-indigo-600">
                                    <i class="fas fa-church"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Ministerio Actual</p>
                                    <p class="font-semibold text-gray-800">{{ $movimiento->ministerio->nombre_ministerio }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                        <div class="flex space-x-3">
                            <a href="{{ route('finanzas.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Cancelar
                            </a>
                            
                            <a href="{{ route('finanzas.show', $movimiento->id_movimiento) }}" 
                               class="bg-blue-200 hover:bg-blue-300 text-blue-800 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-eye mr-2"></i>Ver Detalle
                            </a>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" 
                                    onclick="if(confirm('¿Está seguro de eliminar este movimiento?')) document.getElementById('delete-form').submit();"
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-trash mr-2"></i>Eliminar
                            </button>
                            
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-teal-700 hover:from-green-700 hover:to-teal-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold inline-flex items-center">
                                <i class="fas fa-save mr-2"></i>Actualizar Movimiento
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Formulario de eliminación separado -->
                <form id="delete-form" 
                      action="{{ route('finanzas.destroy', $movimiento->id_movimiento) }}" 
                      method="POST" 
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formatear monto automáticamente
            const montoInput = document.getElementById('monto');
            
            montoInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        });
    </script>
</body>
</html>
</x-app-layout>