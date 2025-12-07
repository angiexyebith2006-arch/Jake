<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programacion extends Model
{
    use HasFactory;

    protected $table = 'programaciones';
    protected $primaryKey = 'id_programacion';
    public $timestamps = false;


protected $fillable = [
    'id_actividad',
    'id_asignacion',
    'fecha',
    'hora_inicio',
    'hora_fin',
    'estado'
];


    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad');
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion');
    }
}
