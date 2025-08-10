<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matriculacion extends Model
{
    use HasFactory;

    // Nombre correcto de la tabla según la migración
    protected $table = 'matriculacions';

    protected $fillable = [
        'estudiante_id',
        'turno_id',
        'gestion_id',
        'nivel_id',
        'grado_id',
        'paralelo_id',
        'fecha_matriculacion',
        'estado'
    ];

    protected $casts = [
        'fecha_matriculacion' => 'date'
    ];

    /**
     * Relación con Estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Relación con Turno
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    /**
     * Relación con Gestión
     */
    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    /**
     * Relación con Nivel
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    /**
     * Relación con Grado
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * Relación con Paralelo
     */
    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class);
    }

    /**
     * Scope para filtrar por gestión
     */
    public function scopeByGestion($query, $gestionId)
    {
        return $query->where('gestion_id', $gestionId);
    }

    /**
     * Scope para filtrar por nivel
     */
    public function scopeByNivel($query, $nivelId)
    {
        return $query->where('nivel_id', $nivelId);
    }

    /**
     * Scope para filtrar por grado
     */
    public function scopeByGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }

    /**
     * Scope para filtrar por paralelo
     */
    public function scopeByParalelo($query, $paraleloId)
    {
        return $query->where('paralelo_id', $paraleloId);
    }

    /**
     * Scope para filtrar por año de matriculación
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('fecha_matriculacion', $year);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeInactivas($query)
    {
        return $query->where('estado', 'inactivo');
    }

    /**
     * Scope para cargar todas las relaciones
     */
    public function scopeWithAllRelations($query)
    {
        return $query->with([
            'estudiante.ppff', 
            'turno', 
            'gestion', 
            'nivel', 
            'grado', 
            'paralelo'
        ]);
    }

    /**
     * Accessor para obtener información completa de la matriculación
     */
    public function getInformacionCompletaAttribute()
    {
        return $this->gestion->nombre . ' - ' . 
               $this->nivel->nombre . ' - ' . 
               $this->grado->nombre . ' - ' . 
               $this->paralelo->nombre . ' (' . 
               $this->turno->nombre . ')';
    }

    /**
     * Verificar si la matriculación está activa
     */
    public function isActive()
    {
        return $this->estado === 'activo';
    }

    /**
     * Método para activar matriculación
     */
    public function activate()
    {
        $this->update(['estado' => 'activo']);
    }

    /**
     * Método para inactivar matriculación
     */
    public function deactivate()
    {
        $this->update(['estado' => 'inactivo']);
    }

    /**
     * Obtener el código único de matriculación
     */
    public function getCodigoMatriculaAttribute()
    {
        return 'M' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '-' . $this->gestion->nombre;
    }

    /**
     * Obtener descripción completa para reportes
     */
    public function getDescripcionCompletaAttribute()
    {
        $estudiante = $this->estudiante->nombre_completo;
        $ci = $this->estudiante->ci;
        $gestion = $this->gestion->nombre;
        $nivel = $this->nivel->nombre;
        $grado = $this->grado->nombre;
        $paralelo = $this->paralelo->nombre;
        
        return "{$estudiante} (CI: {$ci}) - {$gestion} - {$nivel} {$grado} '{$paralelo}'";
    }

    /**
     * Verificar si puede ser editada (reglas de negocio)
     */
    public function canBeEdited()
    {
        // Por ejemplo: no permitir edición después de cierto tiempo
        return $this->estado === 'activo';
    }

    /**
     * Verificar si puede ser eliminada (reglas de negocio)
     */
    public function canBeDeleted()
    {
        // Implementar lógica según reglas de negocio
        // Por ejemplo: no eliminar si tiene calificaciones asociadas
        return true; // Temporalmente permitir eliminación
    }

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Eventos del modelo si son necesarios
        static::creating(function ($matriculacion) {
            // Lógica antes de crear una matriculación
        });

        static::updating(function ($matriculacion) {
            // Lógica antes de actualizar una matriculación
        });
    }

    /**
     * Obtener matriculaciones por rango de fechas
     */
    public function scopeByDateRange($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_matriculacion', [$fechaInicio, $fechaFin]);
    }

    /**
     * Obtener estadísticas básicas
     */
    public static function getEstadisticasBasicas($gestionId = null)
    {
        $query = self::query();
        
        if ($gestionId) {
            $query->where('gestion_id', $gestionId);
        }

        return [
            'total' => $query->count(),
            'activas' => $query->where('estado', 'activo')->count(),
            'inactivas' => $query->where('estado', 'inactivo')->count(),
        ];
    }
}