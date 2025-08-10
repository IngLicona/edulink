<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
    protected $table = 'paralelos';
    protected $fillable = [
        "nombre","grado_id"
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }
}
