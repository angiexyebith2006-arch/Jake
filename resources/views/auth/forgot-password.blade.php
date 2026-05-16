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

        {{-- TÍTULO --}}
        <div class="mb-4 text-center">
            <h2 class="text-lg font-bold text-gray-800">Recuperar contraseña</h2>
            <p class="text-sm text-gray-500 mt-1">Ingresa tu correo y te enviaremos un enlace</p>
        </div>

        {{-- ÉXITO --}}
        @if(session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERRORES --}}
        <x-validation-errors class="mb-4" />

        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('password.forgot.send') }}">
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
                    placeholder="tucorreo@gmail.com"
                    required
                    autofocus
                />

            </div>

            {{-- BOTON ENVIAR --}}
            <div class="mt-6">

                <x-button class="w-full bg-blue-800 text-white py-2 rounded-md justify-center">
                    Enviar enlace de recuperación
                </x-button>

            </div>

            {{-- VOLVER --}}
            <div class="mt-6 text-center border-t pt-4">

                <a href="{{ route('login') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
                    ← Volver al inicio de sesión
                </a>

            </div>

        </form>

    </x-authentication-card>

</x-guest-layout>