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

            <!-- Botón Nueva Actividad -->
            <a href="{{ route('programacion.create') }}"
                class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 mt-4 sm:mt-0">
                <i class="fas fa-plus mr-2"></i> Nueva Programación
            </a>
        </div>

        <!-- Days Tabs -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Selecciona el Día</h2>
                <p class="text-blue-100 text-sm">Elige el día para ver y gestionar las programaciones</p>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                        <button data-dia="{{ $dia }}" 
                            class="dia-btn flex-1 min-w-[120px] bg-white text-blue-600 border-blue-300 hover:bg-blue-50 hover:border-blue-400 px-6 py-4 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold text-center border-2">
                            <i class="fas fa-calendar-day mr-2"></i>{{ $dia }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Programaciones Hoy</p>
                        <p class="text-lg font-bold text-gray-800" id="count-hoy">0</p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-green-100">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Servidores Activos</p>
                        <p class="text-lg font-bold text-gray-800" id="count-servidores">0</p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-purple-100">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="fas fa-hands-praying text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Ministerios Activos</p>
                        <p class="text-lg font-bold text-gray-800" id="count-ministerios">0</p>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pendientes</p>
                        <p class="text-lg font-bold text-gray-800" id="count-pendientes">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-6 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ministerio</label>
                    <select id="ministerioFilter" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los ministerios</option>
                        <option value="1">Alabanza</option>
                        <option value="2">Escuela Dominical</option>
                        <option value="3">Intercesión</option>
                        <option value="4">Servicio</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="estadoFilter" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Confirmado">Confirmado</option>
                        <option value="Reemplazado">Reemplazado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" id="fechaFilter" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button id="aplicarFiltros" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white" id="titulo-tabla">Programaciones - Todos los días</h2>
                <p class="text-blue-100 text-sm" id="subtitulo-tabla">Lista de programaciones y servidores asignados</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                @foreach([
                                    ['Fecha','calendar','blue'],
                                    ['Hora Inicio','clock','green'],
                                    ['Hora Fin','clock','red'],
                                    ['Actividad','tasks','purple'],
                                    ['Ministerio','hands-praying','orange'],
                                    ['Servidor','user','indigo'],
                                    ['Rol','user-tag','yellow'],
                                    ['Estado','info-circle','gray'],
                                    ['Acciones','cog','gray']
                                ] as $col)
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-{{ $col[1] }} mr-2 text-{{ $col[2] }}-500"></i>{{ $col[0] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="tabla-programaciones">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Estado vacío -->
                <div id="estado-vacio" class="text-center py-12 hidden">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay programaciones</h3>
                    <p class="text-gray-500 max-w-md mx-auto">No se encontraron programaciones para los filtros seleccionados.</p>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm mb-4 sm:mb-0">
                        Mostrando <span class="font-semibold" id="contador-mostrando">0</span> de <span class="font-semibold" id="contador-total">0</span> programaciones
                    </p>
                    <div class="flex space-x-2">
                        <button id="btn-anterior" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">1</button>
                        <button id="btn-siguiente" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <script>
        // Datos de ejemplo basados en la estructura de la BD
        const programacionesData = [
            {
                
            }
        ];

        let diaSeleccionado = '';
        let filtrosActuales = {
            ministerio: '',
            estado: '',
            fecha: ''
        };

        document.addEventListener('DOMContentLoaded', function() {
            inicializarPagina();
            cargarProgramaciones();
            actualizarEstadisticas();
        });

        function inicializarPagina() {
            // Event listeners para los botones de día
            document.querySelectorAll('.dia-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const dia = this.getAttribute('data-dia');
                    seleccionarDia(dia);
                });
            });

            // Event listener para filtros
            document.getElementById('aplicarFiltros').addEventListener('click', function() {
                aplicarFiltros();
            });

            // Event listeners para paginación
            document.getElementById('btn-anterior').addEventListener('click', function() {
                // Implementar paginación anterior
            });

            document.getElementById('btn-siguiente').addEventListener('click', function() {
                // Implementar paginación siguiente
            });
        }

        function seleccionarDia(dia) {
            diaSeleccionado = dia;
            
            // Remover clase activa de todos los botones
            document.querySelectorAll('.dia-btn').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-700', 'text-white', 'border-blue-400');
                btn.classList.add('bg-white', 'text-blue-600', 'border-blue-300', 'hover:bg-blue-50', 'hover:border-blue-400');
            });
            
            // Agregar clase activa al botón seleccionado
            const btnSeleccionado = document.querySelector(`[data-dia="${dia}"]`);
            btnSeleccionado.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-700', 'text-white', 'border-blue-400');
            btnSeleccionado.classList.remove('bg-white', 'text-blue-600', 'border-blue-300', 'hover:bg-blue-50', 'hover:border-blue-400');

            // Actualizar título de la tabla
            document.getElementById('titulo-tabla').textContent = `Programaciones - ${dia}`;
            document.getElementById('subtitulo-tabla').textContent = `Lista de programaciones para el ${dia}`;

            // Recargar programaciones con el filtro de día
            cargarProgramaciones();
        }

        function aplicarFiltros() {
            filtrosActuales = {
                ministerio: document.getElementById('ministerioFilter').value,
                estado: document.getElementById('estadoFilter').value,
                fecha: document.getElementById('fechaFilter').value
            };
            cargarProgramaciones();
        }

        function cargarProgramaciones() {
            const tbody = document.getElementById('tabla-programaciones');
            const estadoVacio = document.getElementById('estado-vacio');

            // Filtrar datos
            let datosFiltrados = programacionesData.filter(programacion => {
                let coincide = true;

                // Filtro por día
                if (diaSeleccionado) {
                    const diaProgramacion = obtenerDiaSemana(programacion.fecha);
                    if (diaProgramacion !== diaSeleccionado) {
                        coincide = false;
                    }
                }

                // Filtro por ministerio
                if (filtrosActuales.ministerio && programacion.actividad.ministerio.id_ministerio != filtrosActuales.ministerio) {
                    coincide = false;
                }

                // Filtro por estado
                if (filtrosActuales.estado && programacion.estado !== filtrosActuales.estado) {
                    coincide = false;
                }

                // Filtro por fecha
                if (filtrosActuales.fecha && programacion.fecha !== filtrosActuales.fecha) {
                    coincide = false;
                }

                return coincide;
            });

            if (datosFiltrados.length === 0) {
                tbody.innerHTML = '';
                estadoVacio.classList.remove('hidden');
                document.getElementById('contador-mostrando').textContent = '0';
                document.getElementById('contador-total').textContent = '0';
                return;
            }

            estadoVacio.classList.add('hidden');

            // Generar filas de la tabla
            tbody.innerHTML = datosFiltrados.map(programacion => `
                <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                    <td class="p-4">
                        <div class="flex flex-col">
                            <span class="text-gray-700 font-medium">${formatearFecha(programacion.fecha)}</span>
                            <span class="text-xs text-gray-500">${obtenerDiaSemana(programacion.fecha)}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-clock mr-1"></i>${programacion.hora_inicio}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-clock mr-1"></i>${programacion.hora_fin}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="text-gray-700 font-medium">${programacion.actividad.nombre}</span>
                    </td>
                    <td class="p-4">
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-hands-praying mr-1"></i>${programacion.actividad.ministerio.nombre}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                ${programacion.usuario.iniciales}
                            </div>
                            <span class="text-gray-700 font-medium">${programacion.usuario.nombre}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                            ${programacion.rol.nombre}
                        </span>
                    </td>
                    <td class="p-4">
                        ${generarBadgeEstado(programacion.estado)}
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            <button onclick="editarProgramacion(${programacion.id_programacion})" class="px-2 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>
                            </button>
                            <button onclick="eliminarProgramacion(${programacion.id_programacion})" class="px-2 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>
                            </button>
                            <button onclick="verDetalles(${programacion.id_programacion})" class="px-2 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow transition-all duration-300 transform hover:scale-105 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            // Actualizar contadores
            document.getElementById('contador-mostrando').textContent = datosFiltrados.length;
            document.getElementById('contador-total').textContent = programacionesData.length;
        }

        function generarBadgeEstado(estado) {
            const colores = {
                'Pendiente': 'bg-yellow-100 text-yellow-800',
                'Confirmado': 'bg-green-100 text-green-800',
                'Reemplazado': 'bg-red-100 text-red-800'
            };
            
            return `<span class="${colores[estado]} px-3 py-1 rounded-full text-xs font-semibold">${estado}</span>`;
        }

        function actualizarEstadisticas() {
            const hoy = new Date().toISOString().split('T')[0];
            const programacionesHoy = programacionesData.filter(p => p.fecha === hoy).length;
            const servidoresUnicos = [...new Set(programacionesData.map(p => p.usuario.id_usuario))].length;
            const ministeriosUnicos = [...new Set(programacionesData.map(p => p.actividad.ministerio.id_ministerio))].length;
            const pendientes = programacionesData.filter(p => p.estado === 'Pendiente').length;

            document.getElementById('count-hoy').textContent = programacionesHoy;
            document.getElementById('count-servidores').textContent = servidoresUnicos;
            document.getElementById('count-ministerios').textContent = ministeriosUnicos;
            document.getElementById('count-pendientes').textContent = pendientes;
        }

        // Funciones utilitarias
        function formatearFecha(fecha) {
            return new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES');
        }

        function obtenerDiaSemana(fecha) {
            const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return dias[new Date(fecha + 'T00:00:00').getDay()];
        }

        // Funciones de acción (placeholder)
        function editarProgramacion(id) {
            alert(`Editar programación ${id}`);
            // window.location.href = `/programacion/${id}/edit`;
        }

        function eliminarProgramacion(id) {
            if (confirm('¿Estás seguro de que quieres eliminar esta programación?')) {
                alert(`Eliminar programación ${id}`);
                // Aquí iría la lógica para eliminar
            }
        }

        function verDetalles(id) {
            alert(`Ver detalles de programación ${id}`);
            // window.location.href = `/programacion/${id}`;
        }
    </script>
</body>

</html>
</x-app-layout>