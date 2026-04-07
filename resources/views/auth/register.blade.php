<x-guest-layout style="background-image: url('{{ asset('images/fondo.png') }}');">
    <x-authentication-card
        class="max-w-md w-full mx-auto p-6 bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl rounded-2xl">

        <x-slot name="logo">
            <img src="{{ asset('images/logo_azul.png')}}" alt="Logo JAKE" class="h-12 w-12 rounded-lg">
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           placeholder="Nombre completo"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <!-- Correo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" value="{{ old('correo') }}"
                           placeholder="Correo electrónico"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           placeholder="Teléfono"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <!-- Estado -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                    <select name="activo"
                            class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                        <option class="text-gray-400">Seleccione un estado</option>
                        <option value="1" class="text-green-600">Activo</option>
                        <option value="0" class="text-purple-600">Desactivo</option>
                    </select>
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <!-- Confirmar -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <!-- Botón -->
               <div class="md:col-span-2 mt-6">
                    <button type="submit"
                        class="w-full py-4 text-lg font-bold text-white rounded-2xl shadow-lg transition duration-300"
                        style="background: linear-gradient(to right, #2563eb, #4f46e5); color: white;">
                            Guardar
                    </button>
                </div>

            </div>
        </form>
</body>
    </x-authentication-card>
</x-guest-layout>