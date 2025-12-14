<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambiar si es necesario
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable // Cambia de Model a Authenticatable
{
    use Notifiable;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

     public $timestamps = false; 
    
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'activo',
        'clave', // Asegúrate de tener este campo
    ];
    
    protected $hidden = [
        'clave', // Ocultar el password
        'remember_token',
    ];
    
    protected $casts = [
        'activo' => 'boolean',
    ];

    // Resto de tus relaciones...
}