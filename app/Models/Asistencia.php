<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'asignacion_id',
        'estudiante_id',
        'fecha',
        'estado',
        'observaciones'
    ];

    protected $dates = [
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Constantes para los estados
    const ESTADO_PRESENTE = 'presente';
    const ESTADO_AUSENTE = 'ausente';
    const ESTADO_TARDE = 'tarde';
    const ESTADO_JUSTIFICADO = 'justificado';

    // Relación con Asignación
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Accessor para obtener el estado formateado
    public function getEstadoFormateadoAttribute()
    {
        switch ($this->estado) {
            case self::ESTADO_PRESENTE:
                return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> PRESENTE</span>';
            case self::ESTADO_AUSENTE:
                return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> AUSENTE</span>';
            case self::ESTADO_TARDE:
                return '<span class="badge badge-warning"><i class="fas fa-clock"></i> TARDE</span>';
            case self::ESTADO_JUSTIFICADO:
                return '<span class="badge badge-info"><i class="fas fa-file-medical"></i> JUSTIFICADO</span>';
            default:
                return '<span class="badge badge-secondary">SIN REGISTRO</span>';
        }
    }

    // Accessor para obtener la fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha ? Carbon::parse($this->fecha)->format('d/m/Y') : '';
    }

    // Accessor para obtener el día de la semana
    public function getDiaSemanaAttribute()
    {
        if (!$this->fecha) return '';
        
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes', 
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        
        return $dias[Carbon::parse($this->fecha)->format('l')] ?? '';
    }

    // Scope para filtrar por fecha
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    // Scope para filtrar por rango de fechas
    public function scopePorRangoFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    // Scope para filtrar por asignación
    public function scopePorAsignacion($query, $asignacionId)
    {
        return $query->where('asignacion_id', $asignacionId);
    }

    // Scope para filtrar por estudiante
    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    // Scope para filtrar por estado
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para obtener presentes
    public function scopePresentes($query)
    {
        return $query->where('estado', self::ESTADO_PRESENTE);
    }

    // Scope para obtener ausentes
    public function scopeAusentes($query)
    {
        return $query->where('estado', self::ESTADO_AUSENTE);
    }

    // Scope para obtener tardanzas
    public function scopeTardes($query)
    {
        return $query->where('estado', self::ESTADO_TARDE);
    }

    // Scope para obtener justificados
    public function scopeJustificados($query)
    {
        return $query->where('estado', self::ESTADO_JUSTIFICADO);
    }

    // Método para verificar si es presente (incluye tarde)
    public function esAsistencia()
    {
        return in_array($this->estado, [self::ESTADO_PRESENTE, self::ESTADO_TARDE]);
    }

    // Método para verificar si es ausencia (incluye justificado)
    public function esAusencia()
    {
        return in_array($this->estado, [self::ESTADO_AUSENTE, self::ESTADO_JUSTIFICADO]);
    }

    // Método para obtener el color del estado
    public function getColorEstado()
    {
        switch ($this->estado) {
            case self::ESTADO_PRESENTE:
                return 'success';
            case self::ESTADO_AUSENTE:
                return 'danger';
            case self::ESTADO_TARDE:
                return 'warning';
            case self::ESTADO_JUSTIFICADO:
                return 'info';
            default:
                return 'secondary';
        }
    }

    // Obtener lista de estados disponibles
    public static function getEstados()
    {
        return [
            self::ESTADO_PRESENTE => 'Presente',
            self::ESTADO_AUSENTE => 'Ausente',
            self::ESTADO_TARDE => 'Tarde',
            self::ESTADO_JUSTIFICADO => 'Justificado'
        ];
    }
}