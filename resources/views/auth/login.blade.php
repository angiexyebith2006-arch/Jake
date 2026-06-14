<x-guest-layout>

    <x-authentication-card>

        {{-- LOGO --}}
        <x-slot name="logo">
            <img 
                src="{{ asset('images/logo_negro.png') }}"
                alt="Logo JAKE"
                class="h-12 w-12 rounded-lg"
            >
        </x-slot>

        {{-- ERRORES --}}
        <x-validation-errors class="mb-4" />

        {{-- MENSAJE --}}
        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('login.custom') }}">
            @csrf

            {{-- CORREO --}}
            <div>

                <x-label for="correo" value="Correo electrónico" />

                <x-input
                    id="correo"
                    class="block mt-1 w-full"
                    type="email"
                    name="correo"
                    :value="old('correo')"
                    required
                    autofocus
                />

            </div>

            {{-- CLAVE --}}
            <div class="mt-4">

                <x-label for="clave" value="Clave" />

                <x-input
                    id="clave"
                    class="block mt-1 w-full"
                    type="password"
                    name="clave"
                    required
                />

            </div>

            {{-- RECUPERAR --}}
            <div class="mt-2 text-right">

                <a href="{{ route('password.forgot') }}"
                   class="text-sm text-blue-700 hover:underline">

                    ¿Olvidaste tu contraseña?

                </a>

            </div>

            {{-- BOTON LOGIN --}}
            <div class="mt-4">

                <x-button class="w-full bg-blue-800 text-white py-2 rounded-md justify-center">

                    Iniciar Sesión

                </x-button>

            </div>

            {{-- REGISTRARSE --}}
            <div class="mt-6 text-center border-t pt-4">

                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta?
                </p>

                <a href="{{ route('register') }}"
                   class="mt-3 inline-flex items-center justify-center px-5 py-2 bg-green-600 hover:bg-green-700 text-blue text-sm font-semibold rounded-lg transition">

                    Registrarse

                </a>

            </div>

        </form>

    </x-authentication-card>

</x-guest-layout>