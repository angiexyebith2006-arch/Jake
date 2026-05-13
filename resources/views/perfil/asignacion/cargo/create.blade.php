<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-green-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Nuevo Cargo</h2>
                <p class="text-green-100 text-sm">Ingresa el nombre del rol o posición a registrar</p>
            </div>

            <form method="POST" action="{{ route('cargo.store') }}">
                @csrf

                <div class="p-8 grid grid-cols-1 gap-6">

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
                            Nombre del Cargo
                        </label>
                        <input
                            type="text"
                            name="nombreCargo"
                            value="{{ old('nombreCargo') }}"
                            placeholder="Ej: Instrumentista, Voz líder…"
                            maxlength="80"
                            class="w-full border rounded px-4 py-2"
                            required autofocus>
                        @error('nombreCargo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('cargo.index') }}"
                            class="text-gray-600 hover:text-gray-800 font-medium self-center">
                            ← Volver
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-green-700 hover:bg-green-800 text-white rounded-xl font-semibold hover:scale-105 transition-transform duration-200">
                            Guardar Cargo
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>
</x-app-layout>