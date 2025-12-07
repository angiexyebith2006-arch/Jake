<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionesController;
use App\Http\Controllers\AsignacionesController;

Route::get('/', function () {
    return view('welcome');
});

// 🛠️ MÓDULO PROGRAMACIÓN (CRUD COMPLETO - COORDINADORES)
Route::resource('programacion', ProgramacionesController::class);

Route::resource('perfil', UsuariosController::class);

Route::prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/perfil', [UsuariosController::class, 'index'])
    ->name('perfil.index');

    Route::get('/perfil/create', [UsuariosController::class, 'create'])
    ->name('perfil.create');

    Route::post('/perfil', [UsuariosController::class, 'store'])
    ->name('perfil.store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/perfil', function () {
        return view('perfil.index');
    })->name('perfil.index');

    Route::resource('finanzas', FinanzasController::class);

    Route::get('/chatgrupal', function () {
        return view('chatgrupal.index');
    })->name('chatgrupal.index');
    
    // ✅ MÓDULO ASISTENCIA (VISTA USUARIOS) - DENTRO DEL GRUPO DE AUTENTICACIÓN
    Route::get('/asistencia', [AsignacionesController::class, 'index'])->name('asistencia.index');
    Route::get('/asistencia/{id}', [AsignacionesController::class, 'show'])->name('asistencia.show');
    Route::post('/asistencia/{id}/confirmar', [AsignacionesController::class, 'confirmar'])->name('asistencia.confirmar');
    Route::post('/asistencia/{id}/solicitar-reemplazo', [AsignacionesController::class, 'solicitarReemplazo'])->name('asistencia.solicitar-reemplazo');
});