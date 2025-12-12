<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Financiera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Main Content -->
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Alertas de éxito/error -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión Financiera</h1>
                <p class="text-gray-600">Administración de movimientos financieros de los ministerios</p>
            </div>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <a href="{{ route('finanzas.create') }}" 
                   class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Nuevo Movimiento
                </a>
                <button id="filterBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-xl hover:bg-gray-50 transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Tarjeta Ingresos -->
            <div class="bg-gradient-to-r from-green-600 to-teal-700 shadow-xl rounded-2xl border border-green-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-arrow-down text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Ingresos Totales</h2>
                            <p class="text-green-100 text-sm">Acumulado general</p>
                        </div>
                    </div>
                    <p class="text-white text-2xl font-bold">${{ number_format($totalIngresos, 2) }}</p>
                </div>
            </div>

            <!-- Tarjeta Egresos -->
            <div class="bg-gradient-to-r from-red-600 to-pink-700 shadow-xl rounded-2xl border border-red-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-arrow-up text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Egresos Totales</h2>
                            <p class="text-red-100 text-sm">Acumulado general</p>
                        </div>
                    </div>
                    <p class="text-white text-2xl font-bold">${{ number_format($totalEgresos, 2) }}</p>
                </div>
            </div>

           
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet"
href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
$(function() {
$('#finanzas').DataTable({
pageLength: 20,
dom: 'Bfrtip',

language: {
url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
},
buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});
});
</script>

            <!-- Tarjeta Balance -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl rounded-2xl border border-blue-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-balance-scale text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Balance</h2>
                            <p class="text-blue-100 text-sm">Saldo actual</p>
                        </div>
                    </div>
                    <p class="text-white text-2xl font-bold">${{ number_format($balance, 2) }}</p>
                </div>
            </div>

            <!-- Tarjeta Movimientos -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-700 shadow-xl rounded-2xl border border-purple-500 overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 text-white">
                            <i class="fas fa-exchange-alt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Movimientos</h2>
                            <p class="text-purple-100 text-sm">Total registrados</p>
                        </div>
                    </div>
                    <p class="text-white text-2xl font-bold">{{ $totalMovimientos }}</p>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div id="filterSection" class="bg-white p-6 rounded-2xl shadow-lg mb-8 border border-gray-200 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h3>
            <form method="GET" action="{{ route('finanzas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ministerio</label>
                    <select name="id_ministerio" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los ministerios</option>
                        @foreach($ministerios as $ministerio)
                            <option value="{{ $ministerio->id_ministerio }}" {{ request('id_ministerio') == $ministerio->id_ministerio ? 'selected' : '' }}>
                                {{ $ministerio->nombre_ministerio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ request('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }} ({{ $categoria->tipo }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-4 flex justify-end space-x-3 mt-4">
                    <a href="{{ route('finanzas.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Limpiar
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla principal -->
        <div id="finanzas" class="w-full" style="width:100%">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Movimientos Financieros</h2>
                            <p class="text-gray-600 text-sm">Historial de transacciones financieras</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-list mr-1"></i>
                                Mostrando {{ $movimientos->count() }} registros
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($movimientos->isEmpty())
                        <!-- Estado vacío -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-coins text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay movimientos</h3>
                            <p class="text-gray-500 max-w-md mx-auto">No hay movimientos financieros registrados con los filtros seleccionados.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                    <tr>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-calendar mr-2 text-blue-500"></i>Fecha
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-users mr-2 text-purple-500"></i>Ministerio
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-tag mr-2 text-green-500"></i>Categoría
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-exchange-alt mr-2 text-green-500"></i>Tipo
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>Valor
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>Registrado Por
                                        </th>
                                        <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                            <i class="fas fa-cog mr-2 text-gray-500"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movimientos as $movimiento)
                                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                            <td class="p-4">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-700 font-medium">{{ $movimiento->fecha->format('d/m/Y') }}</span>
                                                    <span class="text-xs text-gray-500">{{ $movimiento->fecha->translatedFormat('l') }}</span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                        {{ substr($movimiento->ministerio->nombre_ministerio, 0, 2) }}
                                                    </div>
                                                    <span class="text-gray-700">{{ $movimiento->ministerio->nombre_ministerio }}</span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                {{ $movimiento->categoria->nombre_categoria }}
                                            </td>
                                            <td class="p-4">
                                                @if($movimiento->categoria->tipo == 'Ingreso')
                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                        <i class="fas fa-arrow-down mr-1"></i>Ingreso
                                                    </span>
                                                @else
                                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                        <i class="fas fa-arrow-up mr-1"></i>Egreso
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-4 font-semibold {{ $movimiento->categoria->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format($movimiento->monto, 2) }}
                                            </td>
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    @if($movimiento->registradoPor)
                                                        <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                            {{ substr($movimiento->registradoPor->nombre ?? 'N/A', 0, 2) }}
                                                        </div>
                                                        <span class="text-gray-600">{{ $movimiento->registradoPor->nombre ?? 'N/A' }}</span>
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('finanzas.show', $movimiento->id_movimiento) }}" 
                                                       class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200"
                                                       title="Ver detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('finanzas.edit', $movimiento->id_movimiento) }}" 
                                                       class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors duration-200"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('finanzas.destroy', $movimiento->id_movimiento) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este movimiento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones adicionales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <a href="{{ route('finanzas.reporte') }}" 
               class="bg-gradient-to-r from-purple-600 to-pink-700 hover:from-purple-700 hover:to-pink-800 text-white px-6 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                <i class="fas fa-chart-bar text-2xl mr-3"></i>
                <div>
                    <h3 class="font-bold text-lg">Ver Reportes</h3>
                    <p class="text-purple-100 text-sm">Reportes detallados y gráficos</p>
                </div>
            </a>
            
            <a href="{{ route('finanzas.dashboard') }}" 
               class="bg-gradient-to-r from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white px-6 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                <i class="fas fa-tachometer-alt text-2xl mr-3"></i>
                <div>
                    <h3 class="font-bold text-lg">Dashboard</h3>
                    <p class="text-indigo-100 text-sm">Panel de control financiero</p>
                </div>
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filtros
            document.getElementById('filterBtn').addEventListener('click', function() {
                document.getElementById('filterSection').classList.toggle('hidden');
            });

            // Confirmación para eliminar
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Está seguro de eliminar este movimiento financiero?')) {
                        e.preventDefault();
                    }
                });
            });

            // Manejar mensajes de Laravel
            @if(session('success'))
                mostrarMensaje('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                mostrarMensaje('{{ session('error') }}', 'error');
            @endif
        });

        // Función para mostrar mensajes
        function mostrarMensaje(mensaje, tipo) {
            // Crear contenedor de mensaje si no existe
            let mensajeContainer = document.getElementById('mensajeContainer');
            if (!mensajeContainer) {
                mensajeContainer = document.createElement('div');
                mensajeContainer.id = 'mensajeContainer';
                mensajeContainer.className = 'fixed top-4 right-4 z-50';
                document.body.appendChild(mensajeContainer);
            }

            // Crear mensaje
            const alertDiv = document.createElement('div');
            alertDiv.className = `p-4 mb-4 rounded-lg shadow-lg ${tipo === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 
                                tipo === 'error' ? 'bg-red-100 text-red-800 border border-red-300' : 
                                'bg-blue-100 text-blue-800 border border-blue-300'}`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 
                                   tipo === 'error' ? 'fa-exclamation-circle' : 
                                   'fa-info-circle'} mr-3"></i>
                    <span class="font-medium">${mensaje}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            mensajeContainer.appendChild(alertDiv);

            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>
</x-app-layout>