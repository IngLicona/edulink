<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCalificacion extends Model
{
    protected $fillable = [
        'calificacion_id',
        'estudiante_id',
        'nota',
    ];

    public function calificacion()
    {
        return $this->belongsTo(Calificacion::class);
    }
    
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
