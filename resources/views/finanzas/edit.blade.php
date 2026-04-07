<!DOCTYPE html>
<x-app-layout>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Movimiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 min-h-screen">

<main class="p-6 max-w-4xl mx-auto">

@if ($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow">
        <strong>¡Error!</strong>
        <ul class="mt-2 text-sm">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 flex items-center">
        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white mr-4">
            <i class="fas fa-pen"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Editar Movimiento</h2>
            <p class="text-blue-100 text-sm">Actualiza la información financiera</p>
        </div>
    </div>

    <!-- FORM -->
    <div class="p-6">
        <form action="{{ route('finanzas.update', $movimiento->id_movimiento) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Categoría -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                        <i class="fas fa-tag text-green-500 mr-1"></i> Categoría
                    </label>
                    <select name="id_categoria"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}"
                                {{ $movimiento->id_categoria == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }} ({{ $categoria->tipo_finanza }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Monto -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                        <i class="fas fa-dollar-sign text-yellow-500 mr-1"></i> Monto
                    </label>
                    <input type="number" name="monto" step="0.01"
                        value="{{ $movimiento->monto }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm">
                </div>

                <!-- Fecha -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                        <i class="fas fa-calendar text-blue-500 mr-1"></i> Fecha
                    </label>
                    <input type="date" name="fecha"
                        value="{{ \Carbon\Carbon::parse($movimiento->fecha)->format('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm">
                </div>

                <!-- Tipo dinámico -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                        <i class="fas fa-exchange-alt text-purple-500 mr-1"></i> Tipo
                    </label>

                    <div class="p-3 rounded-xl text-center font-semibold
                        {{ $movimiento->categoria->tipo_finanza == 'Ingreso' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        
                        <i class="fas {{ $movimiento->categoria->tipo_finanza == 'Ingreso' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                        {{ $movimiento->categoria->tipo_finanza }}
                    </div>
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                        <i class="fas fa-align-left text-indigo-500 mr-1"></i> Descripción
                    </label>
                    <textarea name="descripcion" rows="3"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm">{{ $movimiento->descripcion }}</textarea>
                </div>
            </div>

            <!-- INFO CARD -->
            <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border">
                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div class="flex items-center">
                        <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                        <span>ID: <strong>#{{ $movimiento->id_movimiento }}</strong></span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-calendar text-purple-500 mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</span>
                    </div>

                </div>
            </div>

            <!-- BOTONES -->
            <div class="flex justify-between items-center mt-8">

                <div class="space-x-3">
                    <a href="{{ route('finanzas.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-xl shadow">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>

                    <a href="{{ route('finanzas.show', $movimiento->id_movimiento) }}"
                       class="bg-blue-200 hover:bg-blue-300 px-5 py-2 rounded-xl shadow">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </a>
                </div>

                <div class="space-x-3">
                    <button type="button"
                        onclick="if(confirm('¿Eliminar movimiento?')) document.getElementById('delete-form').submit();"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl shadow">
                        <i class="fas fa-trash mr-1"></i> Eliminar
                    </button>

                    <button type="submit"
                        class="bg-gradient-to-r from-green-600 to-teal-700 text-white px-6 py-2 rounded-xl shadow hover:scale-105 transition">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>

            </div>

        </form>

        <!-- DELETE -->
        <form id="delete-form"
              action="{{ route('finanzas.destroy', $movimiento->id_movimiento) }}"
              method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

</main>
</body>
</html>
</x-app-layout>