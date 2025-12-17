<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';

    protected $fillable = [
        'nombre_permiso',
        'descripcion'
    ];

    public $timestamps = false;

    public function funciones()
    {
        return $this->belongsToMany(
            Funcion::class,
            'funcion_permiso',
            'id_permiso',
            'id_funcion'
        );
    }
}