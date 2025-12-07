<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';
    protected $primaryKey = 'id_asignacion';

    protected $fillable = [
        'id_usuario',
        'id_ministerio', 
        'id_rol',
        'fecha_asignacion',
        'activo'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class, 'id_ministerio');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function programaciones()
    {
        return $this->hasMany(Programacion::class, 'id_asignacion');
    }
}