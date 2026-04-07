<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-green-700 shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            <div class="flex justify-between items-center px-6 py-4">
            <div>
                <h2 class="text-xl font-bold text-white">Asignaciones</h2>
                <p class="text-green-100 text-sm">Listado de Asignaciones</p>
            </div>

            <a href="{{ route('asignaciones.create') }}"
            class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-gray-100">
                + Crear
            </a>
            </div>
            

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministerio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($asignaciones as $asig)
                            <tr>
                              
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['idAsignacion'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['usuarioNombre'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['usuarioEmail'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['rolNombre'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['ministerioNombre'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asig['cargoNombre'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 flex gap-2">

                                    <!-- EDITAR -->
                                    <a href="{{ route('asignaciones.edit', $asig['idAsignacion']) }}"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Editar
                                    </a>

                                    <!-- ELIMINAR -->
                                    <button onclick="eliminarAsignacion({{ $asig['idAsignacion'] }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Eliminar
                                    </button>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    No hay asignaciones disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <script>
async function eliminarAsignacion(idAsignacion) {

    if (!confirm('¿Seguro que quieres eliminar esta asignacion?')) return;

    try {
        const response = await fetch(`http://127.0.0.1:5431/api/asignaciones/${id}/`, {
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
</x-app-layout>