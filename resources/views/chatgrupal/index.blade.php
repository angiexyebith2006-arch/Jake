<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Grupal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-6xl p-4">
        <!-- Encabezado -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Chat Grupal</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Buscar Chat">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Panel lateral de grupos -->
            <div class="w-full md:w-1/3 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Grupos Disponibles</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-music text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Grupo Musicos</h3>
                            <p class="text-xs text-gray-500">3 miembros</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-microphone text-purple-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Grupo Voces</h3>
                            <p class="text-xs text-gray-500">12 miembros</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-users text-green-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Grupo DECOM</h3>
                            <p class="text-xs text-gray-500">8 miembros</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área principal del chat -->
            <div class="w-full md:w-2/3 flex flex-col bg-white rounded-lg shadow-md">
                <!-- Encabezado del chat -->
                <div class="p-4 border-b flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-music text-blue-500"></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-800">Grupo Musicos</h2>
                            <p class="text-xs text-gray-500">3 miembros en línea</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-ellipsis-v text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Área de mensajes -->
                <div class="flex-1 p-4 overflow-y-auto max-h-[400px] scrollbar-thin">
                    <!-- Mensaje de bienvenida -->
                    <div class="mb-6 text-center">
                        <div class="inline-block bg-blue-50 rounded-lg px-4 py-2 max-w-xs mx-auto">
                            <p class="text-sm text-gray-700">Bienvenid@ al Chat Grupal, donde podrás encontrar información sobre las programaciones</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Hoy, 10:30 AM</p>
                    </div>

                    <!-- Mensajes de AdminManager -->
                    <div class="mb-4">
                        <div class="flex items-start mb-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user-shield text-indigo-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700">AdminManager</p>
                                <div class="bg-gray-100 rounded-lg p-3 mt-1 max-w-xs">
                                    <p class="text-sm">Recordatorio: La reunión de programación será el viernes a las 3:00 PM.</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">10:32 AM</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user-shield text-indigo-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700">AdminManager</p>
                                <div class="bg-gray-100 rounded-lg p-3 mt-1 max-w-xs">
                                    <p class="text-sm">Por favor, confirmen su asistencia antes del jueves.</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">10:33 AM</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user-shield text-indigo-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700">AdminManager</p>
                                <div class="bg-gray-100 rounded-lg p-3 mt-1 max-w-xs">
                                    <p class="text-sm">Se ha actualizado el calendario de eventos para el próximo mes.</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">10:35 AM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Área de entrada de mensaje -->
                <div class="p-4 border-t">
                    <div class="flex items-center">
                        <div class="flex space-x-2 mr-3">
                            <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-paperclip text-gray-600"></i>
                            </button>
                            <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-600"></i>
                            </button>
                        </div>
                        <div class="flex-1 relative">
                            <input type="text" class="w-full border border-gray-300 rounded-full py-2 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Escribe un mensaje...">
                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center">
                                <i class="fas fa-paper-plane text-white text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funcionalidad básica para cambiar entre grupos
        document.querySelectorAll('.flex.items-center.p-3.hover\\:bg-gray-50').forEach(group => {
            group.addEventListener('click', function() {
                // Remover la clase activa de todos los grupos
                document.querySelectorAll('.flex.items-center.p-3').forEach(g => {
                    g.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
                    g.classList.add('hover:bg-gray-50');
                });
                
                // Añadir la clase activa al grupo seleccionado
                this.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
                this.classList.remove('hover:bg-gray-50');
                
                // Actualizar el nombre del grupo en el área de chat
                const groupName = this.querySelector('h3').textContent;
                document.querySelector('.font-semibold.text-gray-800').textContent = groupName;
            });
        });
    </script>
</body>
</html>
</x-app-layout>