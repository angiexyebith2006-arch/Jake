<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\AutorizacionesController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\RegisterUserController;

// ===============================
//   RUTAS PÚBLICAS
// ===============================

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/login', [LoginUsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginUsuarioController::class, 'login'])->name('login.custom');
Route::post('/logout', [LoginUsuarioController::class, 'logout'])->name('logout');


//registro actualizado
Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterUserController::class, 'register'])
        ->name('register');

    Route::post('/register', [RegisterUserController::class, 'store'])
        ->name('register.store');

});
                                            
// ===============================
//   RUTAS PROTEGIDAS
// ===============================
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware();

// Todas las demás rutas protegidas

    // ===============================
    //        FINANZAS
    // ===============================
    Route::prefix('finanzas')->name('finanzas.')->group(function () {
        Route::get('/', [FinanzasController::class, 'index'])->name('index');
        Route::get('/create', [FinanzasController::class, 'create'])->name('create');
        Route::post('/', [FinanzasController::class, 'store'])->name('store');
        Route::get('/{id}', [FinanzasController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FinanzasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FinanzasController::class, 'update'])->name('update');
        Route::delete('/{id}', [FinanzasController::class, 'destroy'])->name('destroy');

        Route::get('/reportes/general', [FinanzasController::class, 'reporte'])->name('reporte');
        Route::get('/dashboard', [FinanzasController::class, 'dashboard'])->name('dashboard');
    });

    // ===============================
    //        PERFIL USUARIO
    // ===============================
    Route::resource('perfil', UsuariosController::class);

    // ===============================
    //      PROGRAMACIÓN
    // ===============================
// ===============================
//      PROGRAMACIÓN
// ===============================
Route::prefix('programacion')->name('programacion.')->group(function () {
    Route::get('/', [ProgramacionController::class, 'index'])->name('index');
    Route::get('/crear', [ProgramacionController::class, 'create'])->name('create');
    Route::post('/', [ProgramacionController::class, 'store'])->name('store');
    Route::get('/{id}', [ProgramacionController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [ProgramacionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProgramacionController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProgramacionController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/confirmar', [ProgramacionController::class, 'confirmar'])->name('confirmar');
    Route::put('/{id}/cancelar', [ProgramacionController::class, 'cancelar'])->name('cancelar');
});

// Rutas adicionales
Route::get('/programacion/dia/{dia}', [ProgramacionController::class, 'getByDay'])->name('programacion.byDay');
Route::get('/programacion/estadisticas', [ProgramacionController::class, 'getEstadisticas'])->name('programacion.estadisticas');

    // CHAT
    Route::get('/chatgrupal', function () {
        return view('chatgrupal.index');
    })->name('chatgrupal.index');

    // ===============================
    //        ASISTENCIA
    // ===============================
    Route::prefix('asistencia')->name('asistencia.')->group(function () {
        Route::get('/', [AsignacionesController::class, 'index'])->name('index');
        Route::post('/confirmar/{id_programacion}', [AsignacionesController::class, 'confirmarAsistencia'])->name('confirmar');
        Route::post('/solicitar-reemplazo', [AsignacionesController::class, 'solicitarReemplazo'])->name('reemplazo');
    });

    // ===============================
    //      AUTORIZACIONES
    // ===============================
    Route::prefix('autorizaciones')->name('autorizaciones.')->group(function () {
        Route::get('/', [AutorizacionesController::class, 'index'])->name('index');
        Route::post('/', [AutorizacionesController::class, 'store'])->name('store');
        Route::post('/{id}/aprobar', [AutorizacionesController::class, 'aprobar'])->name('aprobar');
        Route::post('/{id}/rechazar', [AutorizacionesController::class, 'rechazar'])->name('rechazar');
    });
    
