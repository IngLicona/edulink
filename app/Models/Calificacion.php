<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $fillable = [
        'asignacion_id',
        'periodo_id',
        'tipo',
        'descripcion',
        'fecha',
    ];
    
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }
    
    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function detalleCalificaciones()
    {
        return $this->hasMany(DetalleCalificacion::class);
    }

    
}
