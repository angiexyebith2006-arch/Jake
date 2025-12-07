<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reemplazo extends Model
{
    protected $table = 'reemplazos'; // nombre de la tabla
    protected $primaryKey = 'id_reemplazo'; // clave primaria
    public $timestamps = false; // si no usas created_at y updated_at

    protected $fillable = [
        'id_programacion',
        'id_usuario_reemplazado',
        'id_usuario_reemplazo',
        'justificacion',
        'fecha'
    ];

    public function programacion()
    {
        return $this->belongsTo(Programacion::class, 'id_programacion');
    }
}

