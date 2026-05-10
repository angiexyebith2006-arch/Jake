<x-app-layout>
    <main class="p-6 max-w-4xl mx-auto">

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-xl mb-4 font-bold">Editar Asignación</h2>

            {{-- MENSAJE DE ÉXITO --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- MENSAJES DE ERROR --}}
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('asignaciones.update', $asignacion['idAsignacion'] ?? 0) }}">
                @csrf
                @method('PUT')

                {{-- USUARIO --}}
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Usuario</label>
                    <select name="idUsuario" class="border p-2 w-full rounded">
                        <option value="">Seleccione Usuario</option>
                        @foreach($usuarios as $u)
                            @if(is_array($u))
                                <option
                                    value="{{ $u['idUsuario'] ?? '' }}"
                                    {{ ($u['idUsuario'] ?? null) == ($asignacion['idUsuario'] ?? null) ? 'selected' : '' }}
                                >
                                    {{ $u['nombre'] ?? 'Sin nombre' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- ROL --}}
                {{--
                | FIX #4: El Blade anterior usaba $r['idRol'] pero la API de roles
                |         devuelve el campo como $r['id'] (igual que en create.blade.php).
                |         Se corrige para usar $r['id'] en value y en la comparación.
                --}}
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Rol</label>
                    <select name="idRol" class="border p-2 w-full rounded">
                        <option value="">Seleccione Rol</option>
                        @foreach($roles as $r)
                            @if(is_array($r))
                                <option
                                    value="{{ $r['id'] ?? '' }}"
                                    {{ ($r['id'] ?? null) == ($asignacion['idRol'] ?? null) ? 'selected' : '' }}
                                >
                                    {{ $r['nombre'] ?? 'Sin nombre' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- MINISTERIO --}}
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Ministerio</label>
                    <select name="idMinisterio" class="border p-2 w-full rounded">
                        <option value="">Seleccione Ministerio</option>
                        @foreach($ministerios as $m)
                            @if(is_array($m))
                                <option
                                    value="{{ $m['idMinisterio'] ?? '' }}"
                                    {{ ($m['idMinisterio'] ?? null) == ($asignacion['idMinisterio'] ?? null) ? 'selected' : '' }}
                                >
                                    {{ $m['nombreMinisterio'] ?? 'Sin nombre' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- CARGO --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Cargo (Opcional)</label>
                    <select name="idCargo" class="border p-2 w-full rounded">
                        <option value="">Seleccione Cargo</option>
                        @foreach($cargos as $c)
                            @if(is_array($c))
                                <option
                                    value="{{ $c['idCargo'] ?? '' }}"
                                    {{ ($c['idCargo'] ?? null) == ($asignacion['idCargo'] ?? null) ? 'selected' : '' }}
                                >
                                    {{ $c['nombreCargo'] ?? 'Sin nombre' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- BOTONES --}}
                <div class="flex justify-between mt-4">
                    <a href="{{ route('asignaciones.index') }}"
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        ← Volver
                    </a>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Actualizar
                    </button>
                </div>

            </form>
        </div>
    </main>
</x-app-layout>