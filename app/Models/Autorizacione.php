<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacione extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones';
    protected $primaryKey = 'id_autorizacion';
    public $timestamps = false;
    
    // Campos según tu estructura REAL
    protected $fillable = [
        'id_reemplazo',
        'id_autorizador',
        'fecha_autorizacion',
        'observaciones'
    ];

    protected $dates = ['fecha_autorizacion'];

    // Relación con Reemplazo
    public function reemplazo()
    {
        return $this->belongsTo(Reemplazo::class, 'id_reemplazo');
    }

    // Relación con Usuario autorizador
    public function autorizador()
    {
        return $this->belongsTo(Usuario::class, 'id_autorizador');
    }

    // Método para verificar si está pendiente (basado en fecha_autorizacion)
    public function getEstaPendienteAttribute()
    {
        return is_null($this->fecha_autorizacion);
    }

    // Método para aprobar
    public function aprobar($observaciones = null)
    {
        $this->update([
            'fecha_autorizacion' => now(),
            'observaciones' => $observaciones
        ]);
    }

    // Método para rechazar
    public function rechazar($observaciones)
    {
        $this->update([
            'fecha_autorizacion' => now(),
            'observaciones' => $observaciones
        ]);
    }
}