<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-green-700 shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            <div class="flex justify-between items-center px-6 py-4">
    <div>
        <h2 class="text-xl font-bold text-white">Actividades</h2>
        <p class="text-green-100 text-sm">Listado de actividades</p>
    </div>

    <a href="{{ route('actividades.create') }}"
       class="bg-white text-green-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-gray-100">
        + Crear
    </a>
</div>
            

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministerio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Fin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($actividades as $act)
                            <tr>
                              
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['id_ministerio'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['nombre_actividad'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['descripcion'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['hora_inicio'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $act['hora_fin'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 flex gap-2">

                                    <!-- EDITAR -->
                                    <a href="{{ route('actividades.edit', $act['id']) }}"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Editar
                                    </a>

                                    <!-- ELIMINAR -->
                                    <button onclick="eliminarActividad({{ $act['id'] }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Eliminar
                                    </button>

                                </td>

<script>
async function eliminarActividad(id) {

    if (!confirm('¿Seguro que quieres eliminar esta actividad?')) return;

    try {
        const response = await fetch(`http://127.0.0.1:8001/actividades/api/actividades/eliminar/${id}/`, {
            method: 'DELETE'
        });

        if (response.ok) {
            alert('Eliminado correctamente');
            location.reload();
        } else {
            alert('Error al eliminar');
        }

    } catch (error) {
        console.error(error);
        alert('Error de conexión');
    }
}
</script>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    No hay actividades disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</x-app-layout>