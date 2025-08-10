<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Formacion extends Model
{
    protected $table = 'formacions';

    protected $fillable = [
        'personal_id',
        'titulo',
        'institucion',
        'nivel',
        'fecha_graduacion',
        'archivo'
    ];

    protected $casts = [
        'fecha_graduacion' => 'date',
    ];

    // Relación con personal
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    // Accessor para la URL del archivo
    public function getArchivoUrlAttribute()
    {
        if ($this->archivo) {
            return asset('uploads/personal/formacions/' . $this->archivo);
        }
        return null;
    }

    // Accessor para fecha formateada
    public function getFechaGraduacionFormateadaAttribute()
    {
        return Carbon::parse($this->fecha_graduacion)->format('d/m/Y');
    }

    // Niveles disponibles
    public static function getNiveles()
    {
        return [
            'Primaria',
            'Secundaria', 
            'Bachillerato',
            'Técnico',
            'Licenciatura',
            'Especialidad',
            'Maestría',
            'Doctorado'
        ];
    }
}