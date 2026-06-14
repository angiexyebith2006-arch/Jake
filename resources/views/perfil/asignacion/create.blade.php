{{-- ===================== VERSIÓN ORIGINAL ===================== --}}
<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            <div class="bg-gradient-to-r from-green-600 to-green-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-white">Crear Asignación</h2>
                        <p class="text-green-100 text-sm">Asigna un usuario a un rol, ministerio y cargo</p>
                    </div>
                    <a href="{{ route('asignaciones.index') }}"
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        <div class="flex items-center"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('asignaciones.store') }}">
                    @csrf
                    <div class="space-y-6">

                        <!-- Usuario -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-green-500"></i>Usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="idUsuario" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u['idUsuario'] }}" {{ old('idUsuario') == $u['idUsuario'] ? 'selected' : '' }}>
                                        {{ $u['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rol -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-shield mr-2 text-purple-500"></i>Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="idRol" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" required>
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r['id'] }}" {{ old('idRol') == $r['id'] ? 'selected' : '' }}>
                                        {{ $r['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ministerio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="far fa-heart mr-2 text-green-500"></i>Ministerio <span class="text-red-500">*</span>
                            </label>
                            <select name="idMinisterio" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" required>
                                <option value="">Seleccione un ministerio</option>
                                @foreach($ministerios as $m)
                                    <option value="{{ $m['idMinisterio'] }}" {{ old('idMinisterio') == $m['idMinisterio'] ? 'selected' : '' }}>
                                        {{ $m['nombreMinisterio'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cargo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-briefcase mr-2 text-orange-500"></i>Cargo
                                <span class="text-gray-400 text-xs font-normal">(Opcional)</span>
                            </label>
                            <select name="idCargo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition">
                                <option value="">Seleccione un cargo (opcional)</option>
                                @foreach($cargos as $c)
                                    <option value="{{ $c['idCargo'] }}" {{ old('idCargo') == $c['idCargo'] ? 'selected' : '' }}>
                                        {{ $c['nombreCargo'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('asignaciones.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                                <i class="fas fa-save mr-2"></i> Guardar Asignación
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>