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

       <form method="POST" action="{{ route('login.custom') }}">
            @csrf

            <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <main class="p-6 max-w-7xl mx-auto">

            <div>
                <x-label for="correo" value="Correo electrónico" />
                <x-input id="correo" class="block mt-1 w-full"
                         type="email"
                         name="correo"
                         required />
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