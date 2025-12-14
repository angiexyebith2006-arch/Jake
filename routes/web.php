<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionesController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\AutorizacioneController;
use App\Http\Controllers\Auth\RegisteredUserController; // Agregar esta línea
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckPermission;

Route::get('/login', [LoginUsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginUsuarioController::class, 'login'])->name('login.custom');
Route::post('/logout', [LoginUsuarioController::class, 'logout'])->name('logout');
// ===============================
//   RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
// ===============================
Route::get('/', function () {
    return view('welcome');
});

// RUTAS DE AUTENTICACIÓN PERSONALIZADAS
Route::middleware('guest')->group(function () {
    Route::get('register', [UsuariosController::class, 'register'])->name('auth.register');
    Route::post('register', [UsuariosController::class, 'store'])->name('register.store');
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
        Route::get('perfil/create', [UsuariosController::class, 'create'])->name('perfil.create');
        Route::post('perfil', [UsuariosController::class, 'store'])->name('perfil.store');

        Route::get('perfil/edit/{id}', [UsuariosController::class, 'edit'])->name('perfil.edit');
        Route::put('perfil/{id}', [UsuariosController::class, 'update'])->name('perfil.update');

        Route::delete('perfil/{id}', [UsuariosController::class, 'destroy'])->name('perfil.destroy');


        
    
    // ===============================
    //      MÓDULO PROGRAMACIÓN
    // ===============================
Route::prefix('programacion')->name('programacion.')->group(function () {
    Route::get('/', [ProgramacionesController::class, 'index'])->name('index');
    Route::get('/crear', [ProgramacionesController::class, 'create'])->name('create');
    Route::post('/', [ProgramacionesController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [ProgramacionesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProgramacionesController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProgramacionesController::class, 'destroy'])->name('destroy');
    
    // Rutas adicionales para acciones específicas
    Route::post('/{id}/confirmar', [ProgramacionesController::class, 'confirmar'])->name('confirmar');
    Route::put('/{id}/cancelar', [ProgramacionesController::class, 'cancelar'])->name('cancelar');
    Route::put('/{id}/reemplazar', [ProgramacionesController::class, 'reemplazar'])->name('reemplazar');
});


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

    Route::get('asistencia', [AsignacionesController::class, 'index'])->name('asistencia.index');

  // Rutas para asignaciones
Route::prefix('api/asignaciones')->name('asignaciones.')->group(function () {
    // Obtener todas las asignaciones
    Route::get('/', [AsignacionesController::class, 'getAsignaciones'])->name('index');
    
    // Obtener asignaciones del usuario logueado
    Route::get('/mis-asignaciones', [AsignacionesController::class, 'getMisAsignaciones'])->name('mis-asignaciones');
    
    // Obtener asignaciones activas
    Route::get('/activas', [AsignacionesController::class, 'getAsignacionesActivas'])->name('activas');
    
    // Obtener una asignación específica
    Route::get('/{id}', [AsignacionesController::class, 'getAsignacion'])->name('show');
    
    // Crear nueva asignación
    Route::post('/', [AsignacionesController::class, 'crearAsignacion'])->name('store');
    
    // Actualizar asignación
    Route::put('/{id}', [AsignacionesController::class, 'actualizarAsignacion'])->name('update');
    
    // Eliminar/Inactivar asignación
    Route::delete('/{id}', [AsignacionesController::class, 'eliminarAsignacion'])->name('destroy');
    
    // Activar asignación
    Route::put('/{id}/activar', [AsignacionesController::class, 'activarAsignacion'])->name('activar');
});

    Route::get('/debug-api-estructura', [AsignacionesController::class, 'debugApiEstructura']);
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

