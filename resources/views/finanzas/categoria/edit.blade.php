<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-white text-2xl font-bold">Editar Categoría</h2>
                        <p class="text-green-100 text-sm mt-1">Modifica la información de la categoría</p>
                    </div>

                    <a href="{{ route('categorias.index') }}"
                       class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('categorias.update', $categoria->id_categoria) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">

                    {{-- Errores --}}
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong class="font-semibold">Errores encontrados:</strong>
                            </div>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Info categoría --}}
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-green-600"></i>Categoría a editar
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $categoria->nombre_categoria }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    ID: #{{ $categoria->id_categoria }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-font mr-2 text-green-500"></i>Nombre Categoría
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="nombre_categoria"
                               value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               required>
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-layer-group mr-2 text-green-500"></i>Tipo
                            <span class="text-red-500">*</span>
                        </label>

                        <select name="tipo_finanza"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                required>
                            <option value="Ingreso" {{ old('tipo_finanza', $categoria->tipo_finanza) == 'Ingreso' ? 'selected' : '' }}>
                                Ingreso
                            </option>
                            <option value="Egreso" {{ old('tipo_finanza', $categoria->tipo_finanza) == 'Egreso' ? 'selected' : '' }}>
                                Egreso
                            </option>
                        </select>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-500"></i>Descripción
                        </label>

                        <textarea name="descripcion" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                  placeholder="Describe la categoría...">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('categorias.index') }}"
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>

                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Actualizar Categoría
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>
</x-app-layout>