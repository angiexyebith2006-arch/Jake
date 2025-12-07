<x-app-layout>
        <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ingresar Nuevo Movimiento') }}
        </h2>
    </x-slot>
    
@extends('layouts.app')

@section('content')
    <h1>Crear Movimiento</h1>

    <form action="{{ route('finanzas.store') }}" method="POST">
        @csrf

               <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Registro de Movimientos</h2>
                <p class="text-blue-100 text-sm">Ingresa los detalles del movimiento financiero</p>
            </div>

            <div class="p-6">
                <!-- Tipo de Movimiento -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Movimiento</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-green-500 hover:bg-green-50 cursor-pointer transition-all duration-200 group flex-1">
                            <input type="radio" name="tipo" class="h-5 w-5 text-green-600 focus:ring-green-500">
                            <span class="ml-3 font-medium text-gray-700 group-hover:text-green-600">
                                <i class="fas fa-arrow-down text-green-500 mr-2"></i>Ingreso
                            </span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-red-500 hover:bg-red-50 cursor-pointer transition-all duration-200 group flex-1">
                            <input type="radio" name="tipo" class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <span class="ml-3 font-medium text-gray-700 group-hover:text-red-600">
                                <i class="fas fa-arrow-up text-red-500 mr-2"></i>Egreso
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-green-500 mr-2"></i>Valor
                        </label>
                        <input type="number" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" placeholder="$0">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-blue-500 mr-2"></i>Método de Pago
                        </label>
                        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                            <option class="text-gray-400">Seleccione método de pago</option>
                            <option class="text-green-600">Efectivo</option>
                            <option class="text-blue-600">Transferencia</option>
                            <option class="text-purple-600">Tarjeta Débito</option>
                            <option class="text-orange-600">Tarjeta Crédito</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-file-invoice text-indigo-500 mr-2"></i>Detalle
                        </label>
                        <input type="text" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" placeholder="Descripción del movimiento">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users text-purple-500 mr-2"></i>Comité
                        </label>
                        <select class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                            <option class="text-gray-400">Seleccione comité</option>
                            <option>DECOM</option>
                            <option>Alabanza</option>
                            <option>Escuela Dominical</option>
                            <option>Jóvenes</option>
                        </select>
                    </div>
                </div>

                <!-- Action Button -->
                <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold mt-6">
                    <i class="fas fa-save mr-2"></i>Guardar Movimiento
                </button>
            </div>
        </div>
        <label>Nombre</label>
        <input type="text" name="nombre">

        <button type="submit">Guardar</button>
    </form>
@endsection
</x-app-layout>