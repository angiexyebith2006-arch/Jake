<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Nuevo Ministerio</h2>
                <p class="text-blue-100 text-sm">Completa la información para registrar el ministerio</p>
            </div>

            <form method="POST" action="{{ route('ministerio.store') }}">
                @csrf

                <div class="p-8 grid grid-cols-1 gap-6">

                    <!-- Errores -->
                    @if($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Nombre -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">
                            Nombre del Ministerio
                        </label>
                        <input
                            type="text"
                            name="nombreMinisterio"
                            value="{{ old('nombreMinisterio') }}"
                            placeholder="Ej: Alabanza, DECOM, Finanzas…"
                            maxlength="100"
                            class="w-full border rounded px-4 py-2"
                            required autofocus>
                        @error('nombreMinisterio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            placeholder="Describe brevemente el rol de este ministerio…"
                            maxlength="300"
                            rows="4"
                            class="w-full border rounded px-4 py-2 resize-none">{{ old('descripcion') }}</textarea>
                        <p class="text-xs text-gray-400 text-right mt-1"><span id="charCount">0</span> / 300</p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('ministerio.index') }}"
                            class="text-gray-600 hover:text-gray-800 font-medium self-center">
                            ← Volver
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl font-semibold hover:scale-105 transition-transform duration-200">
                            Guardar Ministerio
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