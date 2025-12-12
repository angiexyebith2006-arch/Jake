<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reemplazo extends Model
{
    use HasFactory;

    protected $table = 'reemplazos';
    protected $primaryKey = 'id_reemplazo';

    // IMPORTANTE: Desactivar timestamps ya que tu tabla no los tiene
    public $timestamps = false;
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_programacion',
        'id_asignacion_reemplazado',
        'id_asignacion_reemplazo_por',
        'motivo',
        'fecha_solicitud',
        'estado'
    ];

    // Definir los campos de fecha
    protected $dates = ['fecha_solicitud'];

    // Relación con Programación
    public function programacion()
    {
        return $this->belongsTo(Programacion::class, 'id_programacion');
    }

    // Relación con Asignación reemplazada
    public function asignacionReemplazado()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion_reemplazado');
    }

    // Relación con Asignación reemplazo
    public function asignacionReemplazoPor()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion_reemplazo_por');
    }

    // Relación con Autorizaciones
    public function autorizaciones()
    {
        return $this->hasMany(Autorizacione::class, 'id_reemplazo');
    }
    
    // Para manejar la fecha_solicitud automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->fecha_solicitud)) {
                $model->fecha_solicitud = now()->toDateString();
            }
        });
    }
}