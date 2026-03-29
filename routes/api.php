<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriasFinanzasController;
use App\Http\Controllers\Api\FinanzasController;

Route::apiResource('categorias', CategoriasFinanzasController::class);
Route::apiResource('finanzas', FinanzasController::class);