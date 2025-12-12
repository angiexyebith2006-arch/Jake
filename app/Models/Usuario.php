<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    public function asignaciones()
{
    return $this->hasMany(Asignacion::class, 'id_usuario');
}
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'activo'
    ];
}
