<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionesController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\AutorizacioneController;

// ===============================
//   RUTA PRINCIPAL
// ===============================
Route::get('/', function () {
    return view('welcome');
});

// ===============================
//   RUTAS PROTEGIDAS POR LOGIN
// ===============================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ===============================
    //        MÓDULO FINANZAS
    // ===============================
    Route::prefix('finanzas')->name('finanzas.')->group(function () {
        Route::get('/', [FinanzasController::class, 'index'])->name('index');
        Route::get('/create', [FinanzasController::class, 'create'])->name('create');
        Route::post('/', [FinanzasController::class, 'store'])->name('store');
        Route::get('/{id}', [FinanzasController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FinanzasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FinanzasController::class, 'update'])->name('update');
        Route::delete('/{id}', [FinanzasController::class, 'destroy'])->name('destroy');

        // Reportes y Dashboard
        Route::get('/reportes/general', [FinanzasController::class, 'reporte'])->name('reporte');
        Route::get('/dashboard', [FinanzasController::class, 'dashboard'])->name('dashboard');
    });

    // ===============================
    //      MÓDULO PERFIL USUARIOS
    // ===============================
    Route::resource('perfil', UsuariosController::class);

    // ===============================
    //      MÓDULO PROGRAMACIÓN
    // ===============================
    Route::resource('programacion', ProgramacionesController::class);

    // Filtrar por día
    Route::get('/programacion/dia/{dia}', 
        [ProgramacionesController::class, 'getByDay']
    )->name('programacion.byDay');

    // Estadísticas
    Route::get('/programacion/estadisticas', 
        [ProgramacionesController::class, 'getEstadisticas']
    )->name('programacion.estadisticas');

    // Chat grupal
    Route::get('/chatgrupal', function () {
        return view('chatgrupal.index');
    })->name('chatgrupal.index');

    // ===============================
    //          MÓDULO ASISTENCIA
    // ===============================
    Route::prefix('asistencia')->name('asistencia.')->group(function () {

        Route::get('/', [AsignacionesController::class, 'index'])->name('index');
        Route::get('/{id}', [AsignacionesController::class, 'show'])->name('show');

        Route::get('/{id}/usuarios-reemplazo', 
            [AsignacionesController::class, 'getUsuariosReemplazo']
        )->name('usuarios-reemplazo');

        Route::post('/{id}/confirmar', 
            [AsignacionesController::class, 'confirmar']
        )->name('confirmar');

        Route::post('/{id}/solicitar-reemplazo', 
            [AsignacionesController::class, 'solicitarReemplazo']
        )->name('solicitar-reemplazo');

        Route::get('/api/verificar', 
            [AsignacionesController::class, 'verificarApi']
        )->name('verificar-api');
    });

    // ===============================
    //        AUTORIZACIONES
    // ===============================
    Route::prefix('autorizaciones')->name('autorizaciones.')->group(function () {
        Route::get('/', [AutorizacioneController::class, 'index'])->name('index');
        Route::post('/', [AutorizacioneController::class, 'store'])->name('store');
        Route::post('/{id}/aprobar', [AutorizacioneController::class, 'aprobar'])->name('aprobar');
        Route::post('/{id}/rechazar', [AutorizacioneController::class, 'rechazar'])->name('rechazar');
    });
});
