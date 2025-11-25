<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Programación de Actividades</h1>
                <p class="text-gray-600">Gestiona y organiza las actividades de cada día</p>
            </div>
            <button class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
                <i class="fas fa-plus mr-2"></i>Nueva Actividad
            </button>
        </div>

        <!-- Days Tabs -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Selecciona el Día</h2>
                <p class="text-blue-100 text-sm">Elige el día para ver y gestionar las actividades programadas</p>
            </div>
            
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    <button class="flex-1 min-w-[120px] bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center border-2 border-blue-400">
                        <i class="fas fa-calendar-day mr-2"></i>Martes
                    </button>
                    <button class="flex-1 min-w-[120px] bg-white text-blue-600 px-6 py-4 rounded-xl border-2 border-blue-300 hover:bg-blue-50 hover:border-blue-400 transition-all duration-300 transform hover:scale-105 font-semibold text-center shadow-sm">
                        <i class="fas fa-calendar-day mr-2"></i>Jueves
                    </button>
                    <button class="flex-1 min-w-[120px] bg-white text-blue-600 px-6 py-4 rounded-xl border-2 border-blue-300 hover:bg-blue-50 hover:border-blue-400 transition-all duration-300 transform hover:scale-105 font-semibold text-center shadow-sm">
                        <i class="fas fa-calendar-day mr-2"></i>Sábado
                    </button>
                    <button class="flex-1 min-w-[120px] bg-white text-blue-600 px-6 py-4 rounded-xl border-2 border-blue-300 hover:bg-blue-50 hover:border-blue-400 transition-all duration-300 transform hover:scale-105 font-semibold text-center shadow-sm">
                        <i class="fas fa-calendar-day mr-2"></i>Domingo
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Actividades Hoy</p>
                        <p class="text-lg font-bold text-gray-800">4</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-green-100">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Servidores Activos</p>
                        <p class="text-lg font-bold text-gray-800">12</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-purple-100">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="fas fa-hands-praying text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Ministerios</p>
                        <p class="text-lg font-bold text-gray-800">6</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Próxima Actividad</p>
                        <p class="text-lg font-bold text-gray-800">10:00 AM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Actividades Programadas - Martes</h2>
                <p class="text-blue-100 text-sm">Lista de actividades y servidores asignados</p>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>Fecha
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-clock mr-2 text-green-500"></i>Hora
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-hands-praying mr-2 text-purple-500"></i>Ministerio
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-user-friends mr-2 text-orange-500"></i>Servidores
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-user-tag mr-2 text-red-500"></i>Rol
                                </th>
                                <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                    <i class="fas fa-cog mr-2 text-gray-500"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                <td class="p-4 text-gray-700 font-medium">15/08/2023</td>
                                <td class="p-4">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-clock mr-1"></i>10:00 AM
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-music mr-1"></i>Alabanza
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                            AR
                                        </div>
                                        <span class="text-gray-700 font-medium">Angel Ramirez</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Músico
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                <td class="p-4 text-gray-700 font-medium">14/08/2023</td>
                                <td class="p-4">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-clock mr-1"></i>7:00 PM
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-graduation-cap mr-1"></i>Escuela Dominical
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                            LR
                                        </div>
                                        <span class="text-gray-700 font-medium">Luis Rojas</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Maestro
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200 transform hover:scale-110">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm mb-4 sm:mb-0">
                        Mostrando <span class="font-semibold">2</span> de <span class="font-semibold">12</span> actividades
                    </p>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            1
                        </button>
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            2
                        </button>
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
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