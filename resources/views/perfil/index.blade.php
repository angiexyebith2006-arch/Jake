<?php
?>
<x-app-layout>
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
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Perfil</h1>
            <p class="text-gray-600">Administra la información del usuario y sus permisos</p>
        </div>
        <button class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
            <i class="fas fa-plus mr-2"></i>Nuevo Usuario
        </button>
    </div>

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

                    <!-- Avatar -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full shadow-lg flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">BR</span>
                            </div>
                            <div class="absolute bottom-0 right-0 bg-yellow-400 rounded-full p-1 shadow-md">
                                <i class="fas fa-camera text-blue-800 text-xs"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Betzi Liliana Rojas Ruiz</p>
                            <p class="text-sm text-gray-600">Usuario activo</p>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo</label>
                            <div class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800">
                                Betzi Liliana Rojas Ruiz
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                            <div class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                BLiliana@gmail.com
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                            <div class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>
                                +57 1234567891
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
                            <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option class="text-gray-400">Seleccione un rol</option>
                                <option class="text-green-600">Alabanza</option>
                                <option class="text-purple-600">Escuela Dominical</option>
                                <option class="text-blue-600">Líder</option>
                                <option class="text-orange-600">Voluntario</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Nivel Ministerial y Permisos -->
                <div class="space-y-6">
                    <!-- Nivel Ministerial -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 border-l-4 border-yellow-500 pl-3 mb-4">Nivel Ministerial</h3>
                        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 shadow-sm">
                            <option class="text-gray-400">Seleccione nivel</option>
                            <option class="text-green-600">Principiante</option>
                            <option class="text-blue-600">Intermedio</option>
                            <option class="text-purple-600">Avanzado</option>
                            <option class="text-orange-600">Líder</option>
                        </select>
                    </div>

                    <!-- Seguridad y Permisos -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 border-l-4 border-green-500 pl-3 mb-4">Seguridad y Permisos</h3>
                        <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="space-y-4">
                                <label class="flex items-center p-3 rounded-lg hover:bg-white hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <input type="checkbox" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200 group-hover:scale-110">
                                    <span class="ml-3 text-gray-700 font-medium group-hover:text-blue-600 transition-colors duration-200">
                                        <i class="fas fa-eye mr-2 text-blue-500"></i>Visualizar
                                    </span>
                                </label>

                                <label class="flex items-center p-3 rounded-lg hover:bg-white hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <input type="checkbox" class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded transition-all duration-200 group-hover:scale-110">
                                    <span class="ml-3 text-gray-700 font-medium group-hover:text-red-600 transition-colors duration-200">
                                        <i class="fas fa-trash mr-2 text-red-500"></i>Eliminar
                                    </span>
                                </label>

                                <label class="flex items-center p-3 rounded-lg hover:bg-white hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <input type="checkbox" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded transition-all duration-200 group-hover:scale-110">
                                    <span class="ml-3 text-gray-700 font-medium group-hover:text-green-600 transition-colors duration-200">
                                        <i class="fas fa-plus mr-2 text-green-500"></i>Crear
                                    </span>
                                </label>

                                <label class="flex items-center p-3 rounded-lg hover:bg-white hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <input type="checkbox" class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded transition-all duration-200 group-hover:scale-110">
                                    <span class="ml-3 text-gray-700 font-medium group-hover:text-yellow-600 transition-colors duration-200">
                                        <i class="fas fa-edit mr-2 text-yellow-500"></i>Editar
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                <button class="px-8 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i>Editar Perfil
                </button>
                <button class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-user-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Estado</p>
                    <p class="text-lg font-bold text-gray-800">Activo</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-green-100">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Miembro desde</p>
                    <p class="text-lg font-bold text-gray-800">Ene 2024</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-purple-100">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Permisos</p>
                    <p class="text-lg font-bold text-gray-800">4 activos</p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
</x-app-layout>