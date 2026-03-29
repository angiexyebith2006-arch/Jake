<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/logo.png')}}" alt="Logo JAKE" class="h-12 w-12 rounded-lg">
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        {{-- CORREGIDO: Eliminamos el DOCTYPE y HTML anidado --}}
        <form method="POST" action="{{ route('login.custom') }}">
            @csrf

            <div>
                <x-label for="correo" value="Correo electrónico" />
                <x-input id="correo" class="block mt-1 w-full"
                         type="email"
                         name="correo"
                         :value="old('correo')"
                         required 
                         autofocus />
            </div>

            <div class="mt-4">
                <x-label for="clave" value="Clave" />
                <x-input id="clave" class="block mt-1 w-full"
                         type="password"
                         name="clave"
                         required />
            </div>

            <div class="mt-4">
                <x-button class="w-full bg-blue-800 text-white py-2 rounded-md">
                    Iniciar Sesión
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>