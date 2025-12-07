<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Grupal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        .chat-container {
            display: none;
        }
        .chat-container.active {
            display: flex;
        }
        .message-actions {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .message-container:hover .message-actions {
            opacity: 1;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
            min-width: 120px;
        }
        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <main class="p-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Chat Grupal</h1>
                <p class="text-gray-600">Comunícate con los diferentes grupos de servidores</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Panel lateral de grupos -->
            <div class="w-full lg:w-1/3 bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Grupos Disponibles</h2>
                    <p class="text-blue-100 text-sm">Selecciona un grupo para chatear</p>
                </div>
                
                <div class="p-6">
                    <div class="relative mb-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Buscar grupo...">
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Grupo Lunes -->
                        <div class="group-item flex items-center p-4 hover:bg-gray-50 rounded-xl border-l-4 border-transparent cursor-pointer transition-all duration-300 hover:shadow-md" data-group="lunes">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                OL
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Organización Lunes</h3>
                                <p class="text-sm text-gray-600">Último mensaje: Preparativos reunión</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500">8 miembros</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-xs text-green-500 font-medium">En línea</span>
                                </div>
                            </div>
                            <div class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                2
                            </div>
                        </div>
                        
                        <!-- Grupo Martes -->
                        <div class="group-item flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-l-4 border-blue-500 cursor-pointer transition-all duration-300 hover:shadow-md" data-group="martes">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                OM
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Organización Martes</h3>
                                <p class="text-sm text-gray-600">Último mensaje: Recordatorio ensayo</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500">3 miembros</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-xs text-green-500 font-medium">En línea</span>
                                </div>
                            </div>
                            <div class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                3
                            </div>
                        </div>
                        
                        <!-- Grupo Miércoles -->
                        <div class="group-item flex items-center p-4 hover:bg-gray-50 rounded-xl border-l-4 border-transparent cursor-pointer transition-all duration-300 hover:shadow-md" data-group="miercoles">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                OM
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Organización Miércoles</h3>
                                <p class="text-sm text-gray-600">Último mensaje: Cambio de horario</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500">5 miembros</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-xs text-green-500 font-medium">En línea</span>
                                </div>
                            </div>
                            <div class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                1
                            </div>
                        </div>
                        
                        <!-- Grupo Jueves -->
                        <div class="group-item flex items-center p-4 hover:bg-gray-50 rounded-xl border-l-4 border-transparent cursor-pointer transition-all duration-300 hover:shadow-md" data-group="jueves">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                OJ
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">Organización Jueves</h3>
                                <p class="text-sm text-gray-600">Último mensaje: Nueva canción</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500">12 miembros</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-xs text-green-500 font-medium">En línea</span>
                                </div>
                            </div>
                            <div class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                5
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área principal del chat -->
            <div class="w-full lg:w-2/3">
                <!-- Chat Lunes -->
                <div id="chat-lunes" class="chat-container flex flex-col bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                    <!-- Encabezado del chat -->
                    <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                    OL
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Organización Lunes</h2>
                                    <p class="text-green-100 text-sm">8 miembros en línea</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de mensajes -->
                    <div id="messages-lunes" class="flex-1 p-6 overflow-y-auto max-h-[500px] scrollbar-thin">
                        <div class="mb-6 text-center">
                            <div class="inline-block bg-green-50 rounded-xl px-4 py-3 max-w-xs mx-auto border border-green-100">
                                <p class="text-sm text-gray-700">Bienvenid@ al grupo de Organización Lunes</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-1 relative">
                                <input type="text" id="input-lunes" class="w-full border border-gray-300 rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Escribe un mensaje...">
                                <button onclick="sendMessage('lunes')" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-gradient-to-r from-green-600 to-teal-700 hover:from-green-700 hover:to-teal-800 flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Martes -->
                <div id="chat-martes" class="chat-container active flex flex-col bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                    <!-- Encabezado del chat -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                    OM
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Organización Martes</h2>
                                    <p class="text-blue-100 text-sm">3 miembros en línea</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de mensajes -->
                    <div id="messages-martes" class="flex-1 p-6 overflow-y-auto max-h-[500px] scrollbar-thin">
                        <!-- Mensaje de bienvenida -->
                        <div class="mb-6 text-center">
                            <div class="inline-block bg-blue-50 rounded-xl px-4 py-3 max-w-xs mx-auto border border-blue-100">
                                <p class="text-sm text-gray-700">Bienvenid@ al grupo de Organización Martes</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Hoy, 10:30 AM</p>
                        </div>

                        <!-- Mensajes existentes -->
                        <div class="mb-6">
                            <div class="flex items-start mb-4 message-container">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-3 text-white text-sm font-bold">
                                    AM
                                </div>
                                <div class="flex-1 relative">
                                    <div class="flex items-center mb-1">
                                        <p class="text-sm font-semibold text-gray-800">AdminManager</p>
                                        <span class="mx-2 text-gray-400">•</span>
                                        <p class="text-xs text-gray-500">10:32 AM</p>
                                    </div>
                                    <div class="bg-gray-100 rounded-xl p-4 max-w-lg">
                                        <p class="text-sm text-gray-700">Recordatorio: Ensayo general este martes a las 6:00 PM en el santuario.</p>
                                    </div>
                                    <div class="message-actions absolute right-0 top-0">
                                        <button class="message-menu-btn w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 transition-all duration-200">
                                            <i class="fas fa-ellipsis-v text-xs"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg flex items-center">
                                                <i class="fas fa-edit mr-2 text-blue-500"></i>Editar
                                            </button>
                                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-b-lg flex items-center">
                                                <i class="fas fa-trash mr-2"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de entrada de mensaje -->
                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-1 relative">
                                <input type="text" id="input-martes" class="w-full border border-gray-300 rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Escribe un mensaje...">
                                <button onclick="sendMessage('martes')" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Miércoles -->
                <div id="chat-miercoles" class="chat-container flex flex-col bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                    <!-- Encabezado del chat -->
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-700 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                    OM
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Organización Miércoles</h2>
                                    <p class="text-yellow-100 text-sm">5 miembros en línea</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de mensajes -->
                    <div id="messages-miercoles" class="flex-1 p-6 overflow-y-auto max-h-[500px] scrollbar-thin">
                        <div class="mb-6 text-center">
                            <div class="inline-block bg-yellow-50 rounded-xl px-4 py-3 max-w-xs mx-auto border border-yellow-100">
                                <p class="text-sm text-gray-700">Bienvenid@ al grupo de Organización Miércoles</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-1 relative">
                                <input type="text" id="input-miercoles" class="w-full border border-gray-300 rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Escribe un mensaje...">
                                <button onclick="sendMessage('miercoles')" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-gradient-to-r from-yellow-600 to-orange-700 hover:from-yellow-700 hover:to-orange-800 flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Jueves -->
                <div id="chat-jueves" class="chat-container flex flex-col bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                    <!-- Encabezado del chat -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                                    OJ
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Organización Jueves</h2>
                                    <p class="text-purple-100 text-sm">12 miembros en línea</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de mensajes -->
                    <div id="messages-jueves" class="flex-1 p-6 overflow-y-auto max-h-[500px] scrollbar-thin">
                        <div class="mb-6 text-center">
                            <div class="inline-block bg-purple-50 rounded-xl px-4 py-3 max-w-xs mx-auto border border-purple-100">
                                <p class="text-sm text-gray-700">Bienvenid@ al grupo de Organización Jueves</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-1 relative">
                                <input type="text" id="input-jueves" class="w-full border border-gray-300 rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Escribe un mensaje...">
                                <button onclick="sendMessage('jueves')" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let messageIdCounter = 1000;
        let activeDropdown = null;

        // Funcionalidad para cambiar entre chats
        document.querySelectorAll('.group-item').forEach(group => {
            group.addEventListener('click', function() {
                const groupId = this.getAttribute('data-group');
                
                // Remover la clase activa de todos los grupos
                document.querySelectorAll('.group-item').forEach(g => {
                    g.classList.remove('bg-gradient-to-r', 'from-blue-50', 'to-indigo-50', 'border-l-4', 'border-blue-500');
                    g.classList.add('hover:bg-gray-50');
                });
                
                // Añadir la clase activa al grupo seleccionado
                this.classList.add('bg-gradient-to-r', 'from-blue-50', 'to-indigo-50', 'border-l-4', 'border-blue-500');
                this.classList.remove('hover:bg-gray-50');
                
                // Ocultar todos los chats
                document.querySelectorAll('.chat-container').forEach(chat => {
                    chat.classList.remove('active');
                });
                
                // Mostrar el chat seleccionado
                document.getElementById(`chat-${groupId}`).classList.add('active');
            });
        });

        // Función para enviar mensajes
        function sendMessage(group) {
            const input = document.getElementById(`input-${group}`);
            const message = input.value.trim();
            
            if (message) {
                const messagesContainer = document.getElementById(`messages-${group}`);
                const messageId = `msg-${messageIdCounter++}`;
                const timestamp = new Date().toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });

                const messageHTML = `
                    <div id="${messageId}" class="flex items-start mb-4 message-container justify-end">
                        <div class="flex-1 max-w-lg relative">
                            <div class="flex items-center mb-1 justify-end">
                                <p class="text-xs text-gray-500">${timestamp}</p>
                                <span class="mx-2 text-gray-400">•</span>
                                <p class="text-sm font-semibold text-gray-800">Tú</p>
                            </div>
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-4 text-white relative">
                                <p class="text-sm message-content">${message}</p>
                                <div class="message-actions absolute -left-10 top-2">
                                    <button class="message-menu-btn w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white transition-all duration-200">
                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg flex items-center">
                                            <i class="fas fa-edit mr-2 text-blue-500"></i>Editar
                                        </button>
                                        <button class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-b-lg flex items-center">
                                            <i class="fas fa-trash mr-2"></i>Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center ml-3 text-white text-sm font-bold">
                            TU
                        </div>
                    </div>
                `;

                messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
                input.value = '';
                
                // Scroll al final
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Agregar event listeners a los nuevos botones de menú
                addMessageMenuListeners();
                
                // Agregar event listeners específicos para editar y eliminar
                const newMessage = document.getElementById(messageId);
                const editBtn = newMessage.querySelector('.edit-btn');
                const deleteBtn = newMessage.querySelector('.delete-btn');
                
                editBtn.addEventListener('click', function() {
                    editMessage(messageId);
                });
                
                deleteBtn.addEventListener('click', function() {
                    deleteMessage(messageId);
                });
            }
        }

        // Enviar mensaje con Enter
        document.querySelectorAll('input[placeholder="Escribe un mensaje..."]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const group = this.id.split('-')[1];
                    sendMessage(group);
                }
            });
        });

        // Funcionalidad para los menús de mensajes
        function addMessageMenuListeners() {
            document.querySelectorAll('.message-menu-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Cerrar dropdown activo si existe
                    if (activeDropdown) {
                        activeDropdown.classList.remove('show');
                    }
                    
                    // Mostrar/ocultar el dropdown actual
                    const dropdown = this.nextElementSibling;
                    dropdown.classList.toggle('show');
                    activeDropdown = dropdown.classList.contains('show') ? dropdown : null;
                });
            });
        }

        // Cerrar dropdowns al hacer click fuera
        document.addEventListener('click', function() {
            if (activeDropdown) {
                activeDropdown.classList.remove('show');
                activeDropdown = null;
            }
        });

        // Funciones para editar y eliminar mensajes
        function editMessage(messageId) {
            const messageElement = document.getElementById(messageId);
            if (messageElement) {
                const messageContent = messageElement.querySelector('.message-content');
                const currentText = messageContent.textContent;
                const newText = prompt('Editar mensaje:', currentText);
                
                if (newText !== null && newText.trim() !== '') {
                    messageContent.textContent = newText.trim();
                    
                    // Agregar indicador de editado
                    const timestampElement = messageElement.querySelector('.text-xs.text-gray-500');
                    if (!timestampElement.innerHTML.includes('(editado)')) {
                        timestampElement.innerHTML += ' <span class="text-gray-400">(editado)</span>';
                    }
                }
            }
            
            // Cerrar el dropdown
            if (activeDropdown) {
                activeDropdown.classList.remove('show');
                activeDropdown = null;
            }
        }

        function deleteMessage(messageId) {
            if (confirm('¿Estás seguro de que quieres eliminar este mensaje?')) {
                const messageElement = document.getElementById(messageId);
                if (messageElement) {
                    messageElement.remove();
                }
            }
            
            // Cerrar el dropdown
            if (activeDropdown) {
                activeDropdown.classList.remove('show');
                activeDropdown = null;
            }
        }

        // Agregar event listeners para los mensajes existentes
        document.addEventListener('DOMContentLoaded', function() {
            addMessageMenuListeners();
            
            // Agregar event listeners para editar y eliminar en mensajes existentes
            document.querySelectorAll('.message-container').forEach(container => {
                const editBtn = container.querySelector('.dropdown-menu button:first-child');
                const deleteBtn = container.querySelector('.dropdown-menu button:last-child');
                
                if (editBtn) {
                    editBtn.addEventListener('click', function() {
                        const messageId = container.id;
                        if (messageId) {
                            editMessage(messageId);
                        }
                    });
                }
                
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        const messageId = container.id;
                        if (messageId) {
                            deleteMessage(messageId);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
</x-app-layout>