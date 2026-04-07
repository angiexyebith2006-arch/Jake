<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 rounded-t-xl">
            <h2 class="text-xl font-bold text-white">Editar Actividad</h2>
            <p class="text-blue-100 text-sm">Actualizar información</p>
        </div>

        <!-- FORM -->
        <div class="bg-white shadow-xl rounded-b-xl p-6">
            <form id="formEdit">

                <input type="hidden" id="id" value="{{ $id ?? '' }}">

                <!-- NOMBRE -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="descripcion"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required></textarea>
                </div>

                <!-- HORAS -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                        <input type="time" id="hora_inicio"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Fin</label>
                        <input type="time" id="hora_fin"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
                        Actualizar
                    </button>

                    <a href="{{ route('actividades.index') }}"
                        class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600">
                        Volver
                    </a>
                </div>

            </form>
        </div>

    </div>

    <!-- SCRIPT -->
    <script>
    const id = document.getElementById('id').value;

    // 🔹 Cargar datos
    fetch(`http://127.0.0.1:8001/actividades/api/actividades/${id}/`)
    .then(res => res.json())
    .then(data => {
        const actividad = data.data;

        document.getElementById('nombre').value = actividad.nombre_actividad || '';
        document.getElementById('descripcion').value = actividad.descripcion || '';
        document.getElementById('hora_inicio').value = actividad.hora_inicio 
    ? actividad.hora_inicio.substring(0,5) 
    : '';

document.getElementById('hora_fin').value = actividad.hora_fin 
    ? actividad.hora_fin.substring(0,5) 
    : '';
    })
    .catch(err => console.error(err));

    // 🔹 Actualizar
    document.getElementById('formEdit').addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = {
            id_ministerio: 1,
            nombre_actividad: document.getElementById('nombre').value,
            descripcion: document.getElementById('descripcion').value,
            hora_inicio: document.getElementById('hora_inicio').value + ":00",
            hora_fin: document.getElementById('hora_fin').value + ":00"
        };

        try {
            const response = await fetch(`http://127.0.0.1:8001/actividades/api/actividades/actualizar/${id}/`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('Actividad actualizada');
                window.location.href = "{{ route('actividades.index') }}";
            } else {
                const error = await response.json();
                console.error(error);
                alert('Error al actualizar');
            }

        } catch (error) {
            console.error(error);
            alert('Error de conexión');
        }
    });
    </script>

</x-app-layout>