<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-white text-2xl font-bold">Editar Movimiento</h2>
                        <p class="text-green-100 text-sm mt-1">Modifica la información del movimiento financiero</p>
                    </div>
                    <a href="{{ route('finanzas.index') }}"
                       class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('finanzas.update', $movimiento->id_movimiento) }}" method="POST">
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

                    {{-- Info del movimiento --}}
                    <div class="bg-gradient-to-r from-green-50 to-green-50 rounded-xl p-4 border border-green-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-coins mr-2 text-green-600"></i>Movimiento a editar
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-green-500 flex items-center justify-center text-white font-bold text-sm">
                                <i class="fas fa-exchange-alt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-800">
                                    ${{ number_format($movimiento->monto, 2) }} — {{ $movimiento->categoria->nombre_categoria }}
                                </p>
                                <p class="text-sm text-gray-500">ID: #{{ $movimiento->id_movimiento }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Categoría --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-green-500"></i>Categoría
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="id_categoria"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                required>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}"
                                    {{ $movimiento->id_categoria == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre_categoria }} ({{ $cat->tipo_finanza }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Monto --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Monto
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="monto" step="0.01" min="0"
                               value="{{ old('monto', $movimiento->monto) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               required>
                        @error('monto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-green-500"></i>Fecha
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="fecha"
                               value="{{ old('fecha', \Carbon\Carbon::parse($movimiento->fecha)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               required>
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-500"></i>Descripción
                        </label>
                        <textarea name="descripcion" rows="3"
                                  placeholder="Descripción opcional del movimiento..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Campo opcional. Describe el motivo del movimiento.</p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('finanzas.index') }}"
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Actualizar Movimiento
                        </button>
                    </div>

                </div>
            </form>

            {{-- Form oculto eliminar --}}
            <form id="delete-form"
                  action="{{ route('finanzas.destroy', $movimiento->id_movimiento) }}"
                  method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </main>
</x-app-layout>