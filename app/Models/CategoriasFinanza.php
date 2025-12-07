<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriasFinanza extends Model
{
    // Nombre de la tabla (porque no sigue la convención)
    protected $table = 'categorias_finanzas';

    // Nombre de la llave primaria
    protected $primaryKey = 'id_categoria';

    // Si NO tienes timestamps (created_at, updated_at)
    public $timestamps = false;

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre_categoria',
        'tipo',
        'descripcion',
    ];
}