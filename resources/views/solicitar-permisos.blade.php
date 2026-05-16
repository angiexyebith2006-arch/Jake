<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Solicitar Permisos</h2>
                <p class="text-blue-100 text-sm">Completa el formulario para solicitar roles y permisos</p>
            </div>

            <div class="p-6">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('enviar.solicitud.permisos') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Selección de Roles (Múltiples) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            🎭 Roles Solicitados * (Puedes seleccionar varios)
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Selecciona todos los roles que necesitas para tus funciones</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_solicitados[]" value="admin" class="w-4 h-4 text-purple-600 rounded">
                                <div class="ml-3">
                                    <span class="font-medium">👑 Administrador</span>
                                    <p class="text-xs text-gray-500">Acceso total a todos los módulos del sistema</p>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_solicitados[]" value="tesorero" class="w-4 h-4 text-green-600 rounded">
                                <div class="ml-3">
                                    <span class="font-medium">💰 Tesorero</span>
                                    <p class="text-xs text-gray-500">Acceso a finanzas, reportes y autorizaciones</p>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_solicitados[]" value="lider" class="w-4 h-4 text-blue-600 rounded">
                                <div class="ml-3">
                                    <span class="font-medium">⭐ Líder</span>
                                    <p class="text-xs text-gray-500">Acceso a programaciones, asistencia y chat grupal</p>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_solicitados[]" value="usuario" class="w-4 h-4 text-gray-600 rounded">
                                <div class="ml-3">
                                    <span class="font-medium">👤 Usuario</span>
                                    <p class="text-xs text-gray-500">Acceso básico limitado</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Módulos Específicos Adicionales -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            📦 Módulos Específicos Adicionales
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Puedes seleccionar módulos adicionales a los que ya incluye tu rol</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="programaciones" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">📅 Programaciones</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="usuarios" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">👥 Usuarios</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="roles" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">⚙️ Roles y Permisos</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="reportes" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">📊 Reportes</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="asistencia" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">✅ Asistencia</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="finanzas" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">💰 Finanzas</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="autorizaciones" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">📝 Autorizaciones</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permisos_adicionales[]" value="chat_grupal" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-3 font-medium">💬 Chat Grupal</span>
                            </label>
                        </div>
                    </div>

                    <!-- Justificación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Justificación *</label>
                        <textarea name="justificacion" rows="5" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe detalladamente por qué necesitas estos roles y permisos..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres</p>
                    </div>

                    <!-- Resumen de permisos por rol -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">ℹ️ Permisos por Rol</h4>
                        <div class="text-sm text-blue-700 space-y-2">
                            <div><strong>👑 Admin:</strong> Todos los módulos</div>
                            <div><strong>💰 Tesorero:</strong> Finanzas, Reportes, Autorizaciones</div>
                            <div><strong>⭐ Líder:</strong> Programaciones, Asistencia, Chat Grupal</div>
                            <div><strong>👤 Usuario:</strong> Acceso básico</div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('perfil.show', session('usuario_api.id_usuario') ?? 0) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>