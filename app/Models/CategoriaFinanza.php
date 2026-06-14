<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Finanza;

class CategoriaFinanza extends Model
{
    protected $table = 'categorias_finanzas';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria',
        'tipo_finanza',
        'descripcion',
    ];

    // Limpia espacios y normaliza tipo_finanza antes de guardar
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->tipo_finanza = trim($model->tipo_finanza);
            $model->nombre_categoria = trim($model->nombre_categoria);
        });
    }

    public function finanzas()
    {
        return $this->hasMany(Finanza::class, 'id_categoria', 'id_categoria');
    }
}