<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Finanzas
 * 
 * @property int $id_movimiento
 * @property int $id_ministerio
 * @property int $id_categoria
 * @property float $monto
 * @property Carbon $fecha
 * @property string|null $descripcion
 * @property int|null $registrado_por
 * 
 * @property Ministerio $ministerio
 * @property CategoriasFinanza $categoria
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Finanzas extends Model
{
    protected $table = 'finanzas'; // o 'movimientos' según tu BD
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $casts = [
        'id_ministerio' => 'int',
        'id_categoria' => 'int',
        'monto' => 'float',
        'fecha' => 'datetime',
        'registrado_por' => 'int'
    ];

    protected $fillable = [
        'id_ministerio',
        'id_categoria',
        'monto',
        'fecha',
        'descripcion',
        'registrado_por'
    ];

    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class, 'id_ministerio', 'id_ministerio');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriasFinanza::class, 'id_categoria', 'id_categoria');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id_usuario');
    }
}
