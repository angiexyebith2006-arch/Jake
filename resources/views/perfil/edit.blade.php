<x-app-layout>
<form action="{{ route('perfil.update', $usuario->id_usuario) }}" method="POST">
    @csrf
    @method('PUT')


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
    
        <!-- Profile Card -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Información del Usuario</h2>
                <p class="text-blue-100 text-sm">Actualiza los datos del perfil y configura los permisos</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Información Personal -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-800 border-l-4 border-blue-500 pl-3">Información Personal</h3>

 
                        <!-- Form Fields (mostrar como texto, NO inputs)-->
                        <div class="space-y-4">
                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800">Nombre Completo</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}"
                                    class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">Correo Electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}"
                                    class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                                    class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                                <select name="activo">
                                    <option value="1" {{ $usuario->activo ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !$usuario->activo ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="text right">
                    <a href="{{ route('perfil.index') }}" 
                    class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </a>
                    <button type="submit" 
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibol items-center justify-center">
                        Guardar Cambios
                    </button>
                </div>
        </div>
    </main>
</body>
</html>
</form>
</x-app-layout>
