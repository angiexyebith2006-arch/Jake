<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudPermisosController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProgramacionController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\AutorizacionesController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\MinisterioController;
use App\Http\Controllers\AccionController;
use App\Http\Controllers\VistaController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CategoriasFinanzaController;

// ===============================
// RUTAS PÚBLICAS
// ===============================

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/login',  [LoginUsuarioController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginUsuarioController::class, 'login'])->name('login.custom');

// Recuperar contraseña
Route::get('/forgot-password',  [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.forgot.send');
Route::get('/reset-password',   [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password',  [PasswordResetController::class, 'resetPassword'])->name('password.reset.update');

// REGISTRO
Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisterUserController::class, 'register'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
});

// ===============================
// RUTAS PROTEGIDAS
// ===============================

Route::middleware(['api.session'])->group(function () {

    Route::post('/logout', [LoginUsuarioController::class, 'logout'])->name('logout');

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // SOLICITUD DE PERMISOS
    Route::get('/solicitar-permisos',          [SolicitudPermisosController::class, 'create'])->name('solicitar.permisos');
    Route::post('/enviar-solicitud-permisos',   [SolicitudPermisosController::class, 'store'])->name('enviar.solicitud.permisos');

    // MI PERFIL
    Route::get('/mi-perfil', function () {
        return view('perfil.mi-perfil');
    })->name('mi-perfil');

    // REPORTES USUARIOS
    Route::get('/perfil/reportes',       [UsuariosController::class, 'reportes'])->name('perfil.reportes');
    Route::get('/perfil/reporte/excel',  [UsuariosController::class, 'reporteExcel'])->name('perfil.reporte.excel');
    Route::get('/perfil/reporte/pdf',    [UsuariosController::class, 'reportePdf'])->name('perfil.reporte.pdf');
    Route::get('/perfil/reporte/csv',    [UsuariosController::class, 'reporteCsv'])->name('perfil.reporte.csv');

    // ACTIVIDADES
    Route::get('/actividades',           [ActividadController::class, 'index'])->name('actividades.index');
    Route::get('/actividades/create',    [ActividadController::class, 'create'])->name('actividades.create');
    Route::get('/actividades/{id}/edit', [ActividadController::class, 'edit'])->name('actividades.edit');

    // PERMISOS
    Route::get('/permisos',           [PermisoController::class, 'index'])->name('permisos.index');
    Route::get('/permisos/create',    [PermisoController::class, 'create'])->name('permisos.create');
    Route::post('/permisos/multiple', [PermisoController::class, 'storeMultiple'])->name('permisos.storeMultiple');
    Route::get('/permisos/{id}/edit', [PermisoController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{id}',      [PermisoController::class, 'update'])->name('permisos.update');
    Route::delete('/permisos/{id}',   [PermisoController::class, 'destroy'])->name('permisos.destroy');

    // ASIGNACIONES
    Route::get('/asignacion',           [AsignacionController::class, 'index'])->name('asignaciones.index');
    Route::get('/asignacion/create',    [AsignacionController::class, 'create'])->name('asignaciones.create');
    Route::get('/asignacion/{id}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');
    Route::post('/asignacion',          [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::put('/asignacion/{id}',      [AsignacionController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignacion/{id}',   [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');

    // ROLES
    Route::get('/rol',           [RolController::class, 'index'])->name('rol.index');
    Route::get('/rol/create',    [RolController::class, 'create'])->name('rol.create');
    Route::post('/rol',          [RolController::class, 'store'])->name('rol.store');
    Route::get('/rol/{id}',      [RolController::class, 'show'])->name('rol.show');
    Route::get('/rol/{id}/edit', [RolController::class, 'edit'])->name('rol.edit');
    Route::put('/rol/{id}',      [RolController::class, 'update'])->name('rol.update');
    Route::delete('/rol/{id}',   [RolController::class, 'destroy'])->name('rol.destroy');

    // FINANZAS
    Route::prefix('finanzas')->name('finanzas.')->group(function () {
        Route::get('/',                 [FinanzasController::class, 'index'])->name('index');
        Route::get('/create',           [FinanzasController::class, 'create'])->name('create');
        Route::get('/dashboard',        [FinanzasController::class, 'dashboard'])->name('dashboard');
        Route::get('/reportes/general', [FinanzasController::class, 'reporte'])->name('reporte');
        Route::get('/reporte/excel',    [FinanzasController::class, 'reporteExcel'])->name('reporte.excel');
        Route::get('/reporte/pdf',      [FinanzasController::class, 'reportePdf'])->name('reporte.pdf');
        Route::get('/reporte/csv',      [FinanzasController::class, 'reporteCsv'])->name('reporte.csv');
        Route::post('/',                [FinanzasController::class, 'store'])->name('store');
        Route::get('/{id}',             [FinanzasController::class, 'show'])->name('show');
        Route::get('/{id}/edit',        [FinanzasController::class, 'edit'])->name('edit');
        Route::put('/{id}',             [FinanzasController::class, 'update'])->name('update');
        Route::delete('/{id}',          [FinanzasController::class, 'destroy'])->name('destroy');
    });

    // PERFIL
    Route::resource('perfil', UsuariosController::class);

    // PROGRAMACIÓN
    Route::prefix('programacion')->name('programacion.')->group(function () {
        Route::get('/',                [ProgramacionController::class, 'index'])->name('index');
        Route::get('/crear',           [ProgramacionController::class, 'create'])->name('create');
        Route::get('/estadisticas',    [ProgramacionController::class, 'getEstadisticas'])->name('estadisticas');
        Route::get('/dia/{dia}',       [ProgramacionController::class, 'getByDay'])->name('byDay');
        Route::post('/',               [ProgramacionController::class, 'store'])->name('store');
        Route::get('/{id}',            [ProgramacionController::class, 'show'])->name('show');
        Route::get('/{id}/editar',     [ProgramacionController::class, 'edit'])->name('edit');
        Route::put('/{id}',            [ProgramacionController::class, 'update'])->name('update');
        Route::delete('/{id}',         [ProgramacionController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/confirmar', [ProgramacionController::class, 'confirmar'])->name('confirmar');
        Route::put('/{id}/cancelar',   [ProgramacionController::class, 'cancelar'])->name('cancelar');
    });

    // CHAT
    Route::get('/chatgrupal', function () {
        return view('chatgrupal.index');
    })->name('chatgrupal.index');

    // ASISTENCIA
    Route::prefix('asistencia')->name('asistencia.')->group(function () {
        Route::get('/',                                     [ProgramacionController::class, 'asistenciaIndex'])->name('index');
        Route::post('/confirmar/{id_programacion}',         [ProgramacionController::class, 'confirmarAsistencia'])->name('confirmar');
        Route::post('/solicitar-reemplazo',                 [ProgramacionController::class, 'solicitarReemplazo'])->name('reemplazo.solicitar');
        Route::get('/reemplazo/usuarios/{id_programacion}', [ProgramacionController::class, 'getUsuariosReemplazo'])->name('reemplazo.usuarios');
        Route::post('/cancelar/{id_programacion}',          [ProgramacionController::class, 'cancelarAsistencia'])->name('cancelar');
    });

    // AUTORIZACIONES
    Route::prefix('autorizaciones')->group(function () {
        Route::get('/',              [AutorizacionesController::class, 'index'])->name('autorizaciones.index');
        Route::get('/test-urls',     [AutorizacionesController::class, 'testUrls'])->name('autorizaciones.test');
        Route::post('/{id}/aprobar', [AutorizacionesController::class, 'aprobar'])->name('autorizaciones.aprobar');
        Route::post('/{id}/rechazar',[AutorizacionesController::class, 'rechazar'])->name('autorizaciones.rechazar');
    });

    // REPORTES PROGRAMACIÓN
    Route::get('/programacion-reportes',       [ReportesController::class, 'index'])->name('programacion.reportes');
    Route::get('/programacion-reportes/excel', [ReportesController::class, 'exportarExcel'])->name('programacion.reporte.excel');
    Route::get('/programacion-reportes/pdf',   [ReportesController::class, 'exportarPdf'])->name('programacion.reporte.pdf');
    Route::get('/programacion-reportes/csv',   [ReportesController::class, 'exportarCsv'])->name('programacion.reporte.csv');

    // MINISTERIOS
    Route::get('/ministerios',           [MinisterioController::class, 'index'])->name('ministerio.index');
    Route::get('/ministerios/create',    [MinisterioController::class, 'create'])->name('ministerio.create');
    Route::post('/ministerios',          [MinisterioController::class, 'store'])->name('ministerio.store');
    Route::get('/ministerios/{id}/edit', [MinisterioController::class, 'edit'])->name('ministerio.edit');
    Route::put('/ministerios/{id}',      [MinisterioController::class, 'update'])->name('ministerio.update');
    Route::delete('/ministerios/{id}',   [MinisterioController::class, 'destroy'])->name('ministerio.destroy');

    // CARGOS
    Route::get('/cargo',           [CargoController::class, 'index'])->name('cargo.index');
    Route::get('/cargo/create',    [CargoController::class, 'create'])->name('cargo.create');
    Route::post('/cargo',          [CargoController::class, 'store'])->name('cargo.store');
    Route::get('/cargo/{id}/edit', [CargoController::class, 'edit'])->name('cargo.edit');
    Route::put('/cargo/{id}',      [CargoController::class, 'update'])->name('cargo.update');
    Route::delete('/cargo/{id}',   [CargoController::class, 'destroy'])->name('cargo.destroy');

    // ACCIONES
    Route::get('/acciones',             [AccionController::class, 'index'])->name('acciones.index');
    Route::get('/acciones/crear',       [AccionController::class, 'crear'])->name('acciones.crear');
    Route::post('/acciones',            [AccionController::class, 'guardar'])->name('acciones.guardar');
    Route::get('/acciones/{id}/editar', [AccionController::class, 'editar'])->name('acciones.editar');
    Route::put('/acciones/{id}',        [AccionController::class, 'actualizar'])->name('acciones.actualizar');
    Route::delete('/acciones/{id}',     [AccionController::class, 'eliminar'])->name('acciones.eliminar');

    // VISTAS
    Route::get('/vistas',             [VistaController::class, 'index'])->name('vistas.index');
    Route::get('/vistas/crear',       [VistaController::class, 'crear'])->name('vistas.crear');
    Route::post('/vistas',            [VistaController::class, 'guardar'])->name('vistas.guardar');
    Route::get('/vistas/{id}/editar', [VistaController::class, 'editar'])->name('vistas.editar');
    Route::put('/vistas/{id}',        [VistaController::class, 'actualizar'])->name('vistas.actualizar');
    Route::delete('/vistas/{id}',     [VistaController::class, 'eliminar'])->name('vistas.eliminar');

    // CATEGORÍAS
    Route::resource('categorias', CategoriasFinanzaController::class);

});