<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Personal extends Model
{
    protected $table = 'personals';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'nombre',
        'paterno',
        'materno',
        'ci',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'profesion',
        'foto'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con formaciones
    public function formaciones()
    {
        return $this->hasMany(Formacion::class, 'personal_id');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->paterno . ' ' . $this->materno);
    }

    // Accessor para la URL de la foto
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('uploads/personal/fotos/' . $this->foto);
        }
        return null;
    }

    // Accessor para edad
    public function getEdadAttribute()
    {
        if ($this->fecha_nacimiento) {
            return Carbon::parse($this->fecha_nacimiento)->age;
        }
        return null;
    }


    // Relación con Asignaciones
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'docente_id');
    }

    // Scope para docentes
    public function scopeDocentes($query)
    {
        return $query->where('tipo', 'docente');
    }

    
}