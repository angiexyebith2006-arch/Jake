<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Categorías</h2>
                    <p class="text-green-100 text-sm">Gestión de categorías financieras</p>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('finanzas.index') }}"
                       class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>

                    <a href="{{ route('categorias.create') }}"
                       class="bg-green-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-900 transition">
                        <i class="fas fa-plus mr-2"></i> Nueva Categoría
                    </a>
                </div>
            </div>

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Contador --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-tags mr-2 text-green-600"></i>
                        Total categorías registradas:
                        <span class="font-bold text-green-700">{{ $categorias->count() }}</span>
                    </p>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                @if($categorias->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-tags text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay categorías registradas</p>
                        <a href="{{ route('categorias.create') }}"
                           class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Crear categoría
                        </a>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categorias as $categoria)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $categoria->id_categoria }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                        {{ $categoria->nombre_categoria }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                            {{ strtolower($categoria->tipo_finanza) == 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas {{ strtolower($categoria->tipo_finanza) == 'ingreso' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                            {{ ucfirst($categoria->tipo_finanza) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $categoria->descripcion ?? 'Sin descripción' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('categorias.edit', $categoria->id_categoria) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>

                                        <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </main>
</x-app-layout>