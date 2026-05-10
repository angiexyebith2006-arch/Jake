<x-app-layout>
    <main class="p-6 max-w-4xl mx-auto">

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-xl mb-4 font-bold">Crear Asignación</h2>

            {{-- MENSAJE DE ÉXITO --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- MENSAJES DE ERROR --}}
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('asignaciones.store') }}">
                @csrf

                <!-- USUARIO -->
                <div class="mb-3">
                    <label for="idUsuario" class="block mb-1 font-medium">
                        Usuario
                    </label>

                    <select
                        id="idUsuario"
                        name="idUsuario"
                        required
                        class="border p-2 w-full rounded"
                    >
                        <option value="">Seleccione Usuario</option>

                        @foreach($usuarios as $u)
                            <option
                                value="{{ $u['idUsuario'] }}"
                                {{ old('idUsuario') == $u['idUsuario'] ? 'selected' : '' }}
                            >
                                {{ $u['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ROL -->
                <div class="mb-3">
                    <label for="idRol" class="block mb-1 font-medium">
                        Rol
                    </label>

                    <select
                        id="idRol"
                        name="idRol"
                        required
                        class="border p-2 w-full rounded"
                    >
                        <option value="">Seleccione Rol</option>

                        @foreach($roles as $r)
                            <option
                                value="{{ $r['id'] }}"
                                {{ old('idRol') == $r['id'] ? 'selected' : '' }}
                            >
                                {{ $r['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- MINISTERIO -->
                <div class="mb-3">
                    <label for="idMinisterio" class="block mb-1 font-medium">
                        Ministerio
                    </label>

                    <select
                        id="idMinisterio"
                        name="idMinisterio"
                        required
                        class="border p-2 w-full rounded"
                    >
                        <option value="">Seleccione Ministerio</option>

                        @foreach($ministerios as $m)
                            <option
                                value="{{ $m['idMinisterio'] }}"
                                {{ old('idMinisterio') == $m['idMinisterio'] ? 'selected' : '' }}
                            >
                                {{ $m['nombreMinisterio'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CARGO -->
                <div class="mb-4">
                    <label for="idCargo" class="block mb-1 font-medium">
                        Cargo (Opcional)
                    </label>

                    <select
                        id="idCargo"
                        name="idCargo"
                        class="border p-2 w-full rounded"
                    >
                        <option value="">Seleccione Cargo</option>

                        @foreach($cargos as $c)
                            <option
                                value="{{ $c['idCargo'] }}"
                                {{ old('idCargo') == $c['idCargo'] ? 'selected' : '' }}
                            >
                                {{ $c['nombreCargo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- BOTÓN -->
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                >
                    Guardar Asignación
                </button>

            </form>

        </div>
    </main>
</x-app-layout>