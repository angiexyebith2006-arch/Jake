<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Programaciones</h2>
                    <p class="text-blue-100 text-sm">Gestión de programaciones</p>
                </div>
                <a href="{{ route('programacion.create') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50">
                    + Nueva Programación
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 m-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-4 m-4 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($programaciones as $prog)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $prog->id_programacion }}</td>
                                <td class="px-6 py-4">{{ $prog->id_actividad }}</td>
                                <td class="px-6 py-4">{{ $prog->id_asignacion }}</td>
                                <td class="px-6 py-4">{{ $prog->fecha }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($prog->estado == 'confirmado') bg-green-100 text-green-800
                                        @elseif($prog->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $prog->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('programacion.show', $prog->id_programacion) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                    <a href="{{ route('programacion.edit', $prog->id_programacion) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    <form action="{{ route('programacion.destroy', $prog->id_programacion) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar esta programación?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No hay programaciones registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</x-app-layout>