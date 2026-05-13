<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-white text-2xl font-bold">Nuevo Rol</h2>
                        <p class="text-green-100 text-sm mt-1">Define el nombre y descripción del rol de acceso</p>
                    </div>
                    <a href="{{ route('rol.index') }}" 
                       class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('rol.store') }}">
                @csrf

                <div class="p-8 space-y-6">

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

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-green-500"></i>Nombre del Rol
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               placeholder="Ej: Admin, Líder, Tesorero…"
                               maxlength="60"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               required autofocus>
                        <p class="text-xs text-gray-500 mt-1">Máximo 60 caracteres</p>
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-500"></i>Descripción
                        </label>
                        <textarea name="descripcion"
                                  id="descripcion"
                                  placeholder="Describe brevemente qué puede hacer este rol…"
                                  maxlength="255"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition resize-none">{{ old('descripcion') }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">Máximo 255 caracteres</p>
                            <p class="text-xs text-gray-400"><span id="charCount">0</span> / 255</p>
                        </div>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('rol.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Guardar Rol
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ta = document.getElementById('descripcion');
        const cc = document.getElementById('charCount');
        const update = () => cc.textContent = ta.value.length;
        ta.addEventListener('input', update);
        update();
    });
    </script>
</x-app-layout>