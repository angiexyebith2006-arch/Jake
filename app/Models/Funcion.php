<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    // Si tu tabla se llama diferente a "funcions"
    protected $table = 'funcion';

    // Clave primaria si no es "id"
    protected $primaryKey = 'id_funcion';

    // Si no usas timestamps
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_funcion',
        'descripcion',
    ];

    /**
     * Relación con usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_funcion');
    }
}
