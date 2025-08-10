<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación con Personal
    public function personal()
    {
        return $this->hasOne(Personal::class, 'usuario_id');
    }

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'usuario_id');
    }

    // Verificar si es estudiante
    public function esEstudiante()
    {
        return $this->hasRole('ESTUDIANTE');
    }

    // Verificar si es docente
    public function esDocente()
    {
        return $this->hasRole('DOCENTE');
    }

    // Verificar si es administrativo
    public function esAdministrativo()
    {
        return $this->hasRole(['DIRECTOR/A GENERAL', 'SECRETARIO/A']);
    }

    // Verificar si es admin
    public function esAdmin()
    {
        return $this->hasRole('ADMINISTRADOR');
    }

    // Verificar si es director
    public function esDirector()
    {
        return $this->hasRole('DIRECTOR/A GENERAL');
    }

    // Verificar si es secretario
    public function esSecretario()
    {
        return $this->hasRole('SECRETARIO/A');
    }
}