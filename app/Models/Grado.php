<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = "grados";
    protected $fillable= [
            "nombre","nivel_id",
    ];
    public function nivel(){
        return $this->belongsTo(Nivel::class);   
    }

    public function paralelos(){
        return $this->hasMany(Paralelo::class);
    }
}
