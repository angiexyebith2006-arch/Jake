<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña - JAKE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-yellow-400 to-yellow-500 min-h-screen flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">

    {{-- Logo --}}
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="JAKE" class="h-16 w-16 rounded-xl mx-auto mb-3">
        <h1 class="text-2xl font-bold text-gray-800">Nueva contraseña</h1>
        <p class="text-sm text-gray-500 mt-1">Ingresa tu nueva contraseña para continuar</p>
    </div>

    {{-- Errores --}}
    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nueva contraseña</label>
            <input type="password" name="password"
                placeholder="Mínimo 8 caracteres"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition"
                required autofocus>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar contraseña</label>
            <input type="password" name="password_confirmation"
                placeholder="Repite la contraseña"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition"
                required>
        </div>

        <button type="submit"
            class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-800 font-bold py-3 rounded-xl transition-all hover:shadow-lg">
            Cambiar contraseña
        </button>
    </form>

    <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
            ← Volver al inicio de sesión
        </a>
    </div>

</div>
</body>
</html>