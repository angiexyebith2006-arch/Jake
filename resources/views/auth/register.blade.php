<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/logo.png')}}" alt="Logo JAKE" class="h-12 w-12 rounded-lg">
        </x-slot>

        <x-validation-errors class="mb-4" />

      <form action="{{ route('register.store') }}" method="POST">
@csrf

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


                        <!-- Form Fields (mostrar como texto, NO inputs)-->
                        <div class="space-y-4">
                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800">Nombre Completo</label>
                                 <input type="text" name="nombre" value="{{ old('nombre') }}"
                                    placeholder="Nombre completo"
                                    class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">Correo Electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo') }}"
                                     placeholder="Correo electrónico"
                                    class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                                        placeholder="Teléfono"
                                        class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                                <select name="activo" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                    <option class="text-gray-400">Seleccione un estado</option>
                                    <option value="1" class="text-green-600">Activo</option>
                                    <option value="0" class="text-purple-600">Desactivo</option>
                                 </select>
                            </div>

                            <div>
                                <label class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-800 flex items-center">Contraseña</label>
                                    <input type="password" name="clave" value="{{ old('clave') }}"
                                        placeholder=""
                                        class="w-full mb-4 border rounded px-4 py-2">
                            </div>

                                       <!-- Guardar Cambios: se mantiene visual; función queda en edit.blade.php -->
                    <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold flex items-center justify-center">
                        Guardar
                    </button>
                        </div>
                    </div>

                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                    @if(isset($usuarios) && $usuarios->count())
                        
                    @endif

                    <!-- Guardar Cambios: se mantiene visual; función queda en edit.blade.php -->
                </div>
            </div>
        </div>
    </main>
</body>
</html>
</form>
    </x-authentication-card>
</x-guest-layout>