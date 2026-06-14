<x-guest-layout>

    <x-authentication-card>

        {{-- LOGO --}}
        <x-slot name="logo">
            <img
                src="{{ asset('images/Logo_negro.png') }}"
                alt="Logo JAKE"
                class="h-12 w-12 rounded-lg"
            >
        </x-slot>

        {{-- ERRORES --}}
        <x-validation-errors class="mb-4" />

        {{-- FORMULARIO --}}
        <form action="{{ route('register.store') }}" method="POST" >
            @csrf

            {{-- NOMBRE --}}
            <div>
                <x-label for="nombre" value="Nombre Completo" />
                <x-input
                    id="nombre"
                    class="block mt-1 w-full"
                    type="text"
                    name="nombre"
                    :value="old('nombre')"
                    placeholder="Nombre completo"
                    required
                    autofocus
                />
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CORREO / TELÉFONO --}}
            <div class="mt-4 grid grid-cols-2 gap-4">

                <div>
                    <x-label for="correo" value="Correo Electrónico" />
                    <x-input
                        id="correo"
                        class="block mt-1 w-full"
                        type="email"
                        name="correo"
                        :value="old('correo')"
                        placeholder="Correo"
                        required
                    />

                </div>

                <div>
                    <x-label for="telefono" value="Teléfono" />
                    <x-input
                        id="telefono"
                        class="block mt-1 w-full"
                        type="text"
                        name="telefono"
                        :value="old('telefono')"
                        placeholder="Teléfono"
                    />
                    @error('telefono')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ESTADO --}}
            <div class="mt-4">
                <x-label for="activo" value="Estado" />
                <select id="activo" name="activo"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Seleccione un estado</option>
                    <option value="1" {{ old('activo') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo') === '0' ? 'selected' : '' }}>Desactivo</option>
                </select>
                @error('activo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONTRASEÑA / CONFIRMAR --}}
            <div class="mt-4 grid grid-cols-2 gap-4">

                <div>
                    <x-label for="password" value="Contraseña" />
                    <x-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                    />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="password_confirmation" value="Confirmar Contraseña" />
                    <x-input
                        id="password_confirmation"
                        class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                    />
                </div>

            </div>

            {{-- BOTÓN GUARDAR --}}
            <div class="mt-4">
                <x-button class="w-full bg-blue-800 text-white py-2 rounded-md justify-center">
                    Guardar
                </x-button>
            </div>

            {{-- VOLVER AL LOGIN --}}
            <div class="mt-6 text-center border-t pt-4">
                <p class="text-sm text-white-600">¿Ya tienes una cuenta?</p>
                <a href="{{ route('login') }}"
                   class="mt-3 inline-flex items-center justify-center px-5 py-2 bg-green-600 hover:bg-green-700 text-blue text-sm font-semibold rounded-lg transition">
                    Iniciar Sesión
                </a>
            </div>

        </form>

    </x-authentication-card>

</x-guest-layout>