<x-app-layout>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Perfil</title>

            <!-- Tailwind -->
            <script src="https://cdn.tailwindcss.com"></script>

            <!-- Font Awesome -->
            <link
                rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            >
        </head>

        <body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
            <main class="p-6 max-w-7xl mx-auto">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">
                            Gestión de Perfil
                        </h1>
                    </div>

                    <!-- Nuevo Usuario -->
                    <a
                        href="{{ route('perfil.create') }}"
                        class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800
                               text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all
                               duration-300 transform hover:scale-105 mt-4 sm:mt-0 inline-flex items-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Usuario
                    </a>
                   
                </div>

                @if(session('error'))
                    <div class="bg-red-300 text-white p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-300 text-white p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif


                <!-- Tabla -->
                <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

                    <!-- Título de tabla -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">
                            Usuarios Registrados
                        </h2>
                        <p class="text-blue-100 text-sm">
                            Lista de Usuarios
                        </p>
                    </div>

                    <div class="p-6 overflow-x-auto">
                        <table class="w-full text-left text-sm">

                            <!-- Encabezado -->
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    @foreach ([
                                        ['Nombre','calendar','blue'],
                                        ['Correo','envelope','green'],
                                        ['Telefono','phone','purple'],
                                        ['Activo','person','yellow'],
                                    ] as $col)
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-{{ $col[1] }} mr-2 text-{{ $col[2] }}-500"></i>
                                            {{ $col[0] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <!-- Cuerpo -->
                                                    <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">

                                        <td class="p-4 text-gray-700 font-medium">
                                            {{ $usuario->nombre }}
                                        </td>

                                        <td class="p-4">
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $usuario->correo }}
                                            </span>
                                        </td>

                                        <td class="p-4">
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $usuario->telefono }}
                                            </span>
                                        </td>
                                        
                                        <td class="p-4">
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $usuario->activo }}
                                            </span>
                                        </td>
                            

                                        <!-- BOTONES -->
                                        <td class="text-right">

                                            <!-- BOTÓN EDITAR -->
                                            <a href="{{ route('perfil.edit', $usuario->id_usuario) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- BOTÓN ELIMINAR -->
                                            <form action="{{ route('perfil.destroy', $usuario->id_usuario) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <!-- Footer -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200">
                            <p class="text-gray-600 text-sm mb-4 sm:mb-0">
                                Mostrando
                                <span class="font-semibold" id="contador-mostrando">1</span>
                                de
                                <span class="font-semibold" id="contador-total">1</span>
                                Usuarios
                            </p>

                            <div class="flex space-x-2">
                                <button id="btn-anterior" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                    1
                                </button>

                                <button id="btn-siguiente" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </body>
    </html>
</x-app-layout>
