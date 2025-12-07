<?php
?>
<x-app-layout>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

<main class="p-6 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión Financiera</h1>
        </div>
        <button class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
            <a href="{{ route('finanzas.create')}}" class="fas fa-plus mr-2"></a>Nuevo Movimiento
        </button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Registro de Movimientos Card -->
 

        <!-- Reporte Financiero Card -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Reporte Financiero</h2>
                <p class="text-green-100 text-sm">Resumen general de ingresos y egresos</p>
            </div>

            <div class="p-6">
                <!-- Financial Summary -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-4 rounded-xl border border-green-200 text-center">
                        <i class="fas fa-arrow-down text-green-600 text-2xl mb-2"></i>
                        <p class="text-green-700 font-bold text-sm">Total Ingresos</p>
                        <p class="text-green-800 text-2xl font-bold">$1'200.000</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-pink-100 p-4 rounded-xl border border-red-200 text-center">
                        <i class="fas fa-arrow-up text-red-600 text-2xl mb-2"></i>
                        <p class="text-red-700 font-bold text-sm">Total Egresos</p>
                        <p class="text-red-800 text-2xl font-bold">$500.000</p>
                    </div>
                </div>

                <!-- Balance Neto -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-100 p-6 rounded-xl border border-blue-200 text-center mb-6">
                    <i class="fas fa-balance-scale text-blue-600 text-3xl mb-3"></i>
                    <p class="font-bold text-blue-700 text-lg">Balance Neto</p>
                    <p class="text-3xl font-bold text-blue-800">$700.000</p>
                    <p class="text-sm text-blue-600 mt-2">Estado financiero positivo</p>
                </div>

                <!-- Gráfico Placeholder -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-6 rounded-xl border border-gray-200 text-center">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-pie text-white text-3xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm mt-4">Gráfico de Ingresos vs Egresos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Movimientos Recientes Card -->
    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Movimientos Recientes</h2>
            <p class="text-purple-100 text-sm">Historial de transacciones financieras</p>
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
                                <i class="fas fa-users mr-2 text-purple-500"></i>Comité
                            </th>
                            <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                <i class="fas fa-exchange-alt mr-2 text-green-500"></i>Tipo
                            </th>
                            <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                <i class="fas fa-credit-card mr-2 text-blue-500"></i>Método
                            </th>
                            <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>Valor
                            </th>
                            <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                <i class="fas fa-cog mr-2 text-gray-500"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                            <td class="p-4 text-gray-700">15/08/2023</td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">DECOM</span>
                            </td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-arrow-down mr-1"></i>Ingreso
                                </span>
                            </td>
                            <td class="p-4 text-gray-600">Transferencia</td>
                            <td class="p-4 font-semibold text-green-600">$10.000</td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                            <td class="p-4 text-gray-700">14/08/2023</td>
                            <td class="p-4">
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">Alabanza</span>
                            </td>
                            <td class="p-4">
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-arrow-up mr-1"></i>Egreso
                                </span>
                            </td>
                            <td class="p-4 text-gray-600">Efectivo</td>
                            <td class="p-4 font-semibold text-red-600">$20.000</td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-green-100">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Ingresos Totales</p>
                    <p class="text-lg font-bold text-gray-800">$1.2M</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-red-100">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-xl">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Egresos Totales</p>
                    <p class="text-lg font-bold text-gray-800">$500K</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-balance-scale text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Balance</p>
                    <p class="text-lg font-bold text-gray-800">$700K</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-purple-100">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Movimientos</p>
                    <p class="text-lg font-bold text-gray-800">24</p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
</x-app-layout>