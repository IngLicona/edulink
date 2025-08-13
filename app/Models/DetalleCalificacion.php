<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCalificacion extends Model
{
    protected $fillable = [
        'calificacion_id',
        'estudiante_id',
        'nota'
    ];

    protected $casts = [
        'nota' => 'decimal:2'
    ];

    public function calificacion()
    {
        return $this->belongsTo(Calificacion::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Método para obtener la calificación literal
    public function getCalificacionLiteralAttribute()
    {
        if ($this->nota >= 90) {
            return 'Excelente';
        } elseif ($this->nota >= 80) {
            return 'Muy Bueno';
        } elseif ($this->nota >= 70) {
            return 'Bueno';
        } elseif ($this->nota >= 60) {
            return 'Regular';
        } else {
            return 'Deficiente';
        }
    }

    // Método para obtener el estado de aprobación
    public function getEstadoAprobacionAttribute()
    {
        return $this->nota >= 60 ? 'Aprobado' : 'Reprobado';
    }
}