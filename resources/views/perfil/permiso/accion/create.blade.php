<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-white text-2xl font-bold">Crear Acción</h2>
                        <p class="text-green-100 text-sm mt-1">Registra una nueva acción del sistema</p>
                    </div>
                    <a href="{{ route('acciones.index') }}" 
                       class="inline-flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 px-4 py-2 rounded-xl font-semibold text-sm shadow transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('acciones.guardar') }}" method="POST">
                @csrf

                <div class="p-8 space-y-6">

                    <!-- Errores -->
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

                    <!-- Información -->
                    <div class="bg-gradient-to-r from-green-50 to-green-50 rounded-xl p-4 border border-green-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2 text-green-600"></i>Información de la Acción
                        </label>
                        <p class="text-sm text-gray-600">
                            Las acciones representan las operaciones que los usuarios pueden realizar en el sistema.
                            Ejemplos: <strong>crear, editar, eliminar, ver, responder, aprobar, rechazar</strong>
                        </p>
                    </div>

                    <!-- Nombre de la Acción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-green-500"></i>Nombre de la Acción
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nombreAccion"
                               value="{{ old('nombreAccion') }}"
                               placeholder="Ej: crear, editar, eliminar, ver, responder..."
                               maxlength="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               required autofocus>
                        <p class="text-xs text-gray-500 mt-1">Máximo 50 caracteres. Use nombres en minúscula para consistencia.</p>
                        @error('nombreAccion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('acciones.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Guardar Acción
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>
</x-app-layout>