<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\AutorizacionesController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\AsignacionController;

// ===============================
//   RUTAS PÚBLICAS
// ===============================
Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
Route::get('/actividades/create', [ActividadController::class, 'create'])->name('actividades.create');
Route::get('/actividades/{id}/edit', [ActividadController::class, 'edit'])->name('actividades.edit');

Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
Route::get('/permisos/create', [PermisoController::class, 'create'])->name('permisos.create');
Route::get('/permisos/{id}/edit', [PermisoController::class, 'edit'])->name('permisos.edit');

Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
Route::get('/asignaciones/create', [AsignacionController::class, 'create'])->name('asignaciones.create');
Route::get('/asignaciones/{id}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/login', [LoginUsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginUsuarioController::class, 'login'])->name('login.custom');
Route::post('/logout', [LoginUsuarioController::class, 'logout'])->name('logout');

// REGISTRO
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'register'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
});

// DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// FINANZAS
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

// PERFIL USUARIO
Route::resource('perfil', UsuariosController::class);

// PROGRAMACIÓN
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
    Route::get('/dia/{dia}', [ProgramacionController::class, 'getByDay'])->name('byDay');
    Route::get('/estadisticas', [ProgramacionController::class, 'getEstadisticas'])->name('estadisticas');
});

// CHAT
Route::get('/chatgrupal', function () {
    return view('chatgrupal.index');
})->name('chatgrupal.index');

// ASISTENCIA
Route::prefix('asistencia')->name('asistencia.')->group(function () {
    Route::get('/', [AsignacionesController::class, 'index'])->name('index');
    Route::post('/confirmar/{id_programacion}', [AsignacionesController::class, 'confirmarAsistencia'])->name('confirmar');
    Route::post('/solicitar-reemplazo', [AsignacionesController::class, 'solicitarReemplazo'])->name('reemplazo');
});

// AUTORIZACIONES
Route::prefix('autorizaciones')->name('autorizaciones.')->group(function () {
    Route::get('/', [AutorizacionesController::class, 'index'])->name('index');
    Route::post('/', [AutorizacionesController::class, 'store'])->name('store');
    Route::post('/{id}/aprobar', [AutorizacionesController::class, 'aprobar'])->name('aprobar');
    Route::post('/{id}/rechazar', [AutorizacionesController::class, 'rechazar'])->name('rechazar');

    Route::post('/reemplazar', [ReemplazoController::class, 'store'])->name('reemplazar');
    
    });

// ===============================
//   REPORTES - RUTAS FUERA DEL GRUPO (AL FINAL)
// ===============================
Route::get('/programacion-reportes', [ReportesController::class, 'index'])->name('programacion.reportes');
Route::get('/programacion-reportes/excel', [ReportesController::class, 'exportarExcel'])->name('programacion.reporte.excel');
Route::get('/programacion-reportes/pdf', [ReportesController::class, 'exportarPdf'])->name('programacion.reporte.pdf');
Route::get('/programacion-reportes/csv', [ReportesController::class, 'exportarCsv'])->name('programacion.reporte.csv');