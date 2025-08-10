<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'ppffs_id',
        'nombre',
        'paterno',
        'materno',
        'ci',
        'fecha_nacimiento',
        'direccion',
        'genero',
        'telefono',
        'foto',
        'estado'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con PPFF
     */
    public function ppff()
    {
        return $this->belongsTo(Ppff::class, 'ppffs_id');
    }

    /**
     * Relación con Matriculaciones
     */
    public function matriculaciones()
    {
        return $this->hasMany(Matriculacion::class);
    }

    /**
     * Accessor para obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->paterno . ' ' . $this->materno);
    }

    /**
     * Accessor para obtener solo nombres y apellido paterno
     */
    public function getNombreCortoAttribute()
    {
        return trim($this->nombre . ' ' . $this->paterno);
    }

    /**
     * Scope para filtrar estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para filtrar estudiantes inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }

    /**
     * Scope para buscar por nombre o CI
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', '%' . $termino . '%')
              ->orWhere('paterno', 'like', '%' . $termino . '%')
              ->orWhere('materno', 'like', '%' . $termino . '%')
              ->orWhere('ci', 'like', '%' . $termino . '%');
        });
    }

    /**
     * Verificar si el estudiante está activo
     */
    public function isActive()
    {
        return $this->estado === 'activo';
    }

    /**
     * Activar estudiante
     */
    public function activate()
    {
        return $this->update(['estado' => 'activo']);
    }

    /**
     * Inactivar estudiante
     */
    public function deactivate()
    {
        return $this->update(['estado' => 'inactivo']);
    }

    /**
     * Obtener la edad del estudiante
     */
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        
        return $this->fecha_nacimiento->age;
    }

    /**
     * Obtener la ruta completa de la foto
     */
    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }
        
        return asset('uploads/estudiantes/fotos/' . $this->foto);
    }

    /**
     * Verificar si tiene foto
     */
    public function hasFoto()
    {
        return !empty($this->foto) && file_exists(public_path('uploads/estudiantes/fotos/' . $this->foto));
    }

    /**
     * Obtener matriculación activa en una gestión específica
     */
    public function getMatriculacionActiva($gestionId = null)
    {
        $query = $this->matriculaciones()->where('estado', 'activo');
        
        if ($gestionId) {
            $query->where('gestion_id', $gestionId);
        }
        
        return $query->first();
    }

    /**
     * Verificar si está matriculado en una gestión específica
     */
    public function estaMatriculadoEn($gestionId)
    {
        return $this->matriculaciones()
            ->where('gestion_id', $gestionId)
            ->where('estado', 'activo')
            ->exists();
    }
}