<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ppff extends Model
{
    use HasFactory;

    protected $table = 'ppffs';

    protected $fillable = [
        'personal_id',
        'nombre',
        'paterno',
        'materno',
        'ci',
        'fecha_nacimiento',
        'telefono',
        'parentesco',
        'ocupacion',
        'direccion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    // AGREGAR: Contar estudiantes automáticamente
    protected $withCount = ['estudiantes'];

    /**
     * Relación con Personal
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    /**
     * Relación con Estudiantes
     */
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'ppffs_id');
    }

    /**
     * Accessor para obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->paterno . ' ' . ($this->materno ? ' ' . $this->materno : ''));
    }

    /**
     * Accessor para obtener la edad
     */
    public function getEdadAttribute()
    {
        if ($this->fecha_nacimiento) {
            return Carbon::parse($this->fecha_nacimiento)->age;
        }
        return null;
    }

    /**
     * Scope para filtrar por parentesco
     */
    public function scopeByParentesco($query, $parentesco)
    {
        return $query->where('parentesco', $parentesco);
    }

    /**
     * Scope para buscar por nombre, CI o teléfono
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nombre', 'like', "%{$term}%")
              ->orWhere('paterno', 'like', "%{$term}%")
              ->orWhere('materno', 'like', "%{$term}%")
              ->orWhere('ci', 'like', "%{$term}%")
              ->orWhere('telefono', 'like', "%{$term}%");
        });
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeByNombre($query, $nombre)
    {
        return $query->where(function($q) use ($nombre) {
            $q->where('nombre', 'like', "%{$nombre}%")
              ->orWhere('paterno', 'like', "%{$nombre}%")
              ->orWhere('materno', 'like', "%{$nombre}%");
        });
    }

    /**
     * Método para obtener el parentesco formateado
     */
    public function getParentescoFormateadoAttribute()
    {
        $parentescos = [
            'padre' => 'Padre',
            'madre' => 'Madre',
            'tutor' => 'Tutor/a',
            'abuelo' => 'Abuelo',
            'abuela' => 'Abuela',
            'tio' => 'Tío',
            'tia' => 'Tía',
            'hermano' => 'Hermano',
            'hermana' => 'Hermana'
        ];

        return $parentescos[$this->parentesco] ?? ucfirst($this->parentesco);
    }

    /**
     * Método para contar estudiantes asociados
     */
    public function getTotalEstudiantesAttribute()
    {
        return $this->estudiantes()->count();
    }
}