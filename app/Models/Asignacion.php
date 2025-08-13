<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignacions';

    protected $fillable = [
        'docente_id',
        'gestion_id',
        'nivel_id',
        'grado_id',
        'paralelo_id',
        'materia_id',
        'turno_id',
        'estado',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date'
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Personal::class, 'docente_id');
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    // Accessors
    public function getDocenteNombreCompletoAttribute()
    {
        return $this->docente ? $this->docente->paterno . ' ' . $this->docente->materno . ' ' . $this->docente->nombre : 'N/A';
    }

    public function getInformacionComplementariaAttribute()
    {
        return $this->gestion->nombre . ' - ' . 
               $this->nivel->nombre . ' - ' . 
               $this->grado->nombre . ' - ' . 
               $this->paralelo->nombre . ' - ' . 
               $this->turno->nombre;
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', 'inactivo');
    }

    public function scopePorGestion($query, $gestionId)
    {
        return $query->where('gestion_id', $gestionId);
    }

    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }
}