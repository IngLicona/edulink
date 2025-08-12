<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'matriculacion_id',
        'monto',
        'metodo_pago',
        'descripcion',
        'fecha_pago',
        'estado'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2'
    ];

    // Relación con matriculación
    public function matriculacion()
    {
        return $this->belongsTo(Matriculacion::class);
    }

    // Accessor para obtener el nombre del estado formateado
    public function getEstadoFormateadoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'PENDIENTE',
            'completado' => 'COMPLETADO',
            'cancelado' => 'CANCELADO',
            'anulado' => 'ANULADO',
            default => strtoupper($this->estado)
        };
    }

    // Accessor para obtener el método de pago formateado
    public function getMetodoPagoFormateadoAttribute()
    {
        return match($this->metodo_pago) {
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia Bancaria',
            'deposito' => 'Depósito Bancario',
            'cheque' => 'Cheque',
            'tarjeta' => 'Tarjeta de Débito/Crédito',
            default => ucwords($this->metodo_pago)
        };
    }

    // Scopes
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePorGestion($query, $gestionId)
    {
        return $query->whereHas('matriculacion', function ($q) use ($gestionId) {
            $q->where('gestion_id', $gestionId);
        });
    }
}