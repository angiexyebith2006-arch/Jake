<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $primaryKey = 'id_actividad';
    public $timestamps = false;

    protected $fillable = [
        'id_ministerio',
        'nombre_actividad',
        'descripcion'
    ];

    // Relación: actividad pertenece a un ministerio
    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class, 'id_ministerio', 'id_ministerio');
    }

    // Relación: una actividad tiene muchas programaciones
    public function programaciones()
    {
        return $this->hasMany(Programacion::class, 'id_actividad', 'id_actividad');
    }
}
