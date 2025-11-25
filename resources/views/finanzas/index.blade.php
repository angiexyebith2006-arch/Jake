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
            <p class="text-gray-600">Registra y visualiza los movimientos financieros de cada comité</p>
        </div>
        <button class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
            <i class="fas fa-plus mr-2"></i>Nuevo Movimiento
        </button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Registro de Movimientos Card -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Registro de Movimientos</h2>
                <p class="text-blue-100 text-sm">Ingresa los detalles del movimiento financiero</p>
            </div>

            <div class="p-6">
                <!-- Tipo de Movimiento -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Movimiento</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-green-500 hover:bg-green-50 cursor-pointer transition-all duration-200 group flex-1">
                            <input type="radio" name="tipo" class="h-5 w-5 text-green-600 focus:ring-green-500">
                            <span class="ml-3 font-medium text-gray-700 group-hover:text-green-600">
                                <i class="fas fa-arrow-down text-green-500 mr-2"></i>Ingreso
                            </span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-red-500 hover:bg-red-50 cursor-pointer transition-all duration-200 group flex-1">
                            <input type="radio" name="tipo" class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <span class="ml-3 font-medium text-gray-700 group-hover:text-red-600">
                                <i class="fas fa-arrow-up text-red-500 mr-2"></i>Egreso
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-green-500 mr-2"></i>Valor
                        </label>
                        <input type="number" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" placeholder="$0">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-blue-500 mr-2"></i>Método de Pago
                        </label>
                        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                            <option class="text-gray-400">Seleccione método de pago</option>
                            <option class="text-green-600">Efectivo</option>
                            <option class="text-blue-600">Transferencia</option>
                            <option class="text-purple-600">Tarjeta Débito</option>
                            <option class="text-orange-600">Tarjeta Crédito</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-file-invoice text-indigo-500 mr-2"></i>Detalle
                        </label>
                        <input type="text" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" placeholder="Descripción del movimiento">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users text-purple-500 mr-2"></i>Comité
                        </label>
                        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                            <option class="text-gray-400">Seleccione comité</option>
                            <option>DECOM</option>
                            <option>Alabanza</option>
                            <option>Escuela Dominical</option>
                            <option>Jóvenes</option>
                        </select>
                    </div>
                </div>

                <!-- Action Button -->
                <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold mt-6">
                    <i class="fas fa-save mr-2"></i>Guardar Movimiento
                </button>
            </div>
        </div>

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