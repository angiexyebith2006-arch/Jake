<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Movimiento Financiero') }}
        </h2>
    </x-slot>

    <main class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
                <!-- Card Header según tipo -->
                <div class="px-6 py-4 {{ $movimiento->categoria->tipo == 'Ingreso' ? 'bg-gradient-to-r from-green-600 to-teal-700' : 'bg-gradient-to-r from-red-600 to-orange-700' }}">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-white">
                                @if($movimiento->categoria->tipo == 'Ingreso')
                                    <i class="fas fa-arrow-down mr-2"></i>Movimiento de Ingreso
                                @else
                                    <i class="fas fa-arrow-up mr-2"></i>Movimiento de Egreso
                                @endif
                            </h2>
                            <p class="text-gray-100 text-sm">ID: #{{ $movimiento->id_movimiento }}</p>
                        </div>
                        <div class="text-white text-2xl font-bold">
                            ${{ number_format($movimiento->monto, 2) }}
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Información del movimiento -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Ministerio</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    <i class="fas fa-church text-purple-500 mr-2"></i>
                                    {{ $movimiento->ministerio->nombre_ministerio }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Categoría</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    <i class="fas fa-tag {{ $movimiento->categoria->tipo == 'Ingreso' ? 'text-green-500' : 'text-red-500' }} mr-2"></i>
                                    {{ $movimiento->categoria->nombre_categoria }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Fecha</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                    {{ $movimiento->fecha->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Registrado Por</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    <i class="fas fa-user text-gray-500 mr-2"></i>
                                    {{ $movimiento->registradoPor->nombre ?? 'N/A' }}
                                </p>
                                @if($movimiento->registradoPor)
                                    <p class="text-sm text-gray-500">{{ $movimiento->registradoPor->correo }}</p>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Tipo de Movimiento</h3>
                                <p class="mt-1">
                                    @if($movimiento->categoria->tipo == 'Ingreso')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i> Ingreso
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i> Egreso
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Descripción</h3>
                                <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700">{{ $movimiento->descripcion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la categoría -->
                    <div class="mt-8 p-6 bg-blue-50 rounded-xl">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Información de la Categoría</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-blue-600 font-medium">Nombre:</span>
                                <p class="text-blue-800">{{ $movimiento->categoria->nombre_categoria }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-blue-600 font-medium">Tipo:</span>
                                <p class="text-blue-800">{{ $movimiento->categoria->tipo }}</p>
                            </div>
                            @if($movimiento->categoria->descripcion)
                                <div class="col-span-2">
                                    <span class="text-sm text-blue-600 font-medium">Descripción:</span>
                                    <p class="text-blue-800">{{ $movimiento->categoria->descripcion }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <div>
                            <a href="{{ route('finanzas.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Volver a la lista
                            </a>
                        </div>
                        
                        <div class="space-x-2">
                            <a href="{{ route('finanzas.edit', $movimiento->id_movimiento) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-edit mr-2"></i>Editar
                            </a>
                            
                            <form action="{{ route('finanzas.destroy', $movimiento->id_movimiento) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este movimiento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300 inline-flex items-center">
                                    <i class="fas fa-trash mr-2"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>