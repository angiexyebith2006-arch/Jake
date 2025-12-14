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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            
            <!-- DataTables CSS -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
            
            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            
            <!-- DataTables JS -->
            <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
            
            <!-- JSZip para Excel -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
            
            <!-- PDFMake -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
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
                    <div class="flex space-x-4 mt-4 sm:mt-0">
                        <a
                            href="{{ route('perfil.create') }}"
                            class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800
                                   text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all
                                   duration-300 transform hover:scale-105 inline-flex items-center"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Nuevo Usuario
                        </a>
                        
                        <!-- Botón para exportar -->
                        
                    </div>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla -->
                <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

                    <!-- Título de tabla -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    Usuarios Registrados
                                </h2>
                                <p class="text-blue-100 text-sm">
                                    Lista de Usuarios
                                </p>
                            </div>
                            <div class="mt-2 sm:mt-0">
                                <span class="text-sm text-blue-100">
                                    <i class="fas fa-list mr-1"></i>
                                    Mostrando {{ $usuarios->count() }} registros
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 overflow-x-auto">
                        <table id="usuarios-table" class="w-full text-left text-sm">

                            <!-- Encabezado -->
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user mr-2 text-blue-500"></i>
                                        Nombre
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-envelope mr-2 text-green-500"></i>
                                        Correo
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-phone mr-2 text-purple-500"></i>
                                        Teléfono
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-user-check mr-2 text-yellow-500"></i>
                                        Activo
                                    </th>
                                    <th class="p-4 font-semibold text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-cog mr-2 text-gray-500"></i>
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <!-- Cuerpo -->
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                        <td class="p-4 text-gray-700 font-medium">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                    {{ substr($usuario->nombre, 0, 2) }}
                                                </div>
                                                {{ $usuario->nombre }}
                                            </div>
                                        </td>

                                        <td class="p-4">
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $usuario->correo }}
                                            </span>
                                        </td>

                                        <td class="p-4">
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $usuario->telefono }}
                                            </span>
                                        </td>
                                        
                                        <td class="p-4">
                                            @if($usuario->activo)
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>

                                        <!-- BOTONES -->
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <!-- BOTÓN EDITAR -->
                                                <a href="{{ route('perfil.edit', $usuario->id_usuario) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200"
                                                title="Editar">
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

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Inicializar DataTable
                    if ($('#usuarios-table').length) {
                        var table = $('#usuarios-table').DataTable({
                            pageLength: 10,
                            dom: 'Bfrtip',
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
                            },
                            buttons: [
                                {
                                    extend: 'copy',
                                    text: '<i class="fas fa-copy mr-2"></i>Copiar',
                                    className: 'bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: '<i class="fas fa-file-csv mr-2"></i>CSV',
                                    className: 'bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                                    className: 'bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                                    className: 'bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    },
                                    customize: function(doc) {
                                        doc.content[1].table.widths = ['*', '*', '*', '*'];
                                        doc.defaultStyle.fontSize = 10;
                                        doc.styles.tableHeader.fontSize = 11;
                                        doc.styles.title.fontSize = 14;
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                                    className: 'bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded-lg',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    },
                                    customize: function(win) {
                                        $(win.document.body).find('table').addClass('display').css('font-size', '12px');
                                        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                                        $(win.document.body).find('h1').css('text-align','center');
                                        $(win.document.body).find('h1').css('margin-top','20px');
                                    }
                                }
                            ]
                        });

                        // Actualizar contadores
                        function actualizarContadores() {
                            var info = table.page.info();
                            $('#contador-mostrando').text(info.length);
                            $('#contador-total').text(info.recordsTotal);
                        }

                        // Inicializar contadores
                        actualizarContadores();
                        
                        // Actualizar contadores al cambiar de página
                        table.on('draw', function() {
                            actualizarContadores();
                        });

                        // Botón de exportar
                        document.getElementById('exportBtn').addEventListener('click', function() {
                            // Abrir el menú de botones de DataTables
                            $('.dt-buttons .btn').first().trigger('click');
                        });

                        // Botones de paginación personalizados
                        $('#btn-anterior').on('click', function() {
                            table.page('previous').draw('page');
                        });

                        $('#btn-siguiente').on('click', function() {
                            table.page('next').draw('page');
                        });
                    }

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