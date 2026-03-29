<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use App\Models\CategoriaFinanza;

class Finanzas extends Model
{
    protected $table = 'finanzas';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $casts = [
        
        'id_categoria' => 'int',
        'monto' => 'float',
        'fecha' => 'datetime',
    ];

    protected $fillable = [
        
        'id_categoria',
        'monto',
        'fecha',
        'descripcion',
    ];

    

    public function categoria()
    {
        return $this->belongsTo(CategoriaFinanza::class, 'id_categoria', 'id_categoria');
    }

    public function getTipoAttribute()
    {
        return $this->categoria ? $this->categoria->tipo_finanza : null;
    }
}