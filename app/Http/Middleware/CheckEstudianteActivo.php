<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckEstudianteActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar si el usuario es un estudiante basado en su relación
        if (!$user->estudiante) {
            return $next($request);
        }

        // Obtener el estudiante asociado al usuario
        $estudiante = $user->estudiante;
        if (!$estudiante) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'No se encontró la información del estudiante. Por favor, contacta con la administración.');
        }

        // Verificar si el estudiante está activo
        if ($estudiante->estado !== 'activo') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tu cuenta está deshabilitada. Por favor, contacta con la administración.');
        }

        // Verificar matrícula actual
        $matriculaActiva = $estudiante->matriculaciones()
            ->where('estado', 'activo')
            ->whereHas('gestion', function($query) {
                $query->where('estado', 'activo');
            })
            ->exists();

        if (!$matriculaActiva) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'No tienes una matrícula activa para este período escolar. Por favor, contacta con la administración.');
        }

        return $next($request);
    }
}
