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
                                        ['Rol','user-friends','orange'],
                                        ['Nivel Ministerial','user-tag','red']
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
                                <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                    <td class="p-4 text-gray-700 font-medium">
                                        Angie Sarmiento
                                    </td>

                                    <td class="p-4">
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            liderdesorientada@gmail.com
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            3124687594
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full
                                                        flex items-center justify-center text-white text-sm font-bold mr-3">
                                                AR
                                            </div>
                                            <span class="text-gray-700 font-medium">
                                                Lider
                                            </span>
                                        </div>
                                    </td>

                                    <td class="p-4">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Avanzado
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700
                                                       hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl
                                                       shadow-lg transition-all duration-300 transform hover:scale-105
                                                       font-semibold"
                                            >
                                                Editar
                                            </button>

                                            <button
                                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700
                                                       hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl
                                                       shadow-lg transition-all duration-300 transform hover:scale-105
                                                       font-semibold"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
