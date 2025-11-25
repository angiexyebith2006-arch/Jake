<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    })->name('perfil.index'); // <- Aquí se corrigió el error

    Route::get('/asistencia', function () {
         return view('asistencia.index');
    })->name('asistencia.index');

    Route::get('/programacion', function () {
         return view('programacion.index');
    })->name('programacion.index');

    Route::get('/finanzas', function () {
         return view('finanzas.index');
    })->name('finanzas.index');

    Route::get('/chatgrupal', function () {
         return view('chatgrupal.index');
    })->name('chatgrupal.index');
});