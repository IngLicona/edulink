<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'divisa',
        'correo_electronico',
        'web',
        'logotipo'
    ];

    /**
     * Accessor para obtener la URL completa del logotipo
     */
    public function getLogotipoUrlAttribute()
    {
        if ($this->logotipo) {
            return asset('storage/' . $this->logotipo);
        }
        return null;
    }

    /**
     * Scope para obtener la configuración principal
     */
    public static function principal()
    {
        return self::first();
    }
}