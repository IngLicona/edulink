<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Calificacion;
use App\Models\Estudiante;
use App\Models\Personal;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        $rol = implode(', ', $roles);

        if (in_array('ADMINISTRADOR', $roles) || in_array('DIRECTOR/A GENERAL', $roles) || in_array('SECRETARIO/A', $roles)) {
            // Vista para administradores y directores
            $asignaciones = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->where('estado', 'activo')
                ->orderBy('gestion_id', 'desc')
                ->orderBy('nivel_id')
                ->orderBy('grado_id')
                ->orderBy('paralelo_id')
                ->get();
            
            return view('admin.asistencias.index', compact('asignaciones'));
        }
        
        if (in_array('DOCENTE', $roles)) {
            // Vista para docentes
            $docente = Personal::where('usuario_id', $id_usuario)->first();
            $asignaciones = Asignacion::where(['personal_id', $docente->id]);
            
            if (!$docente) {
                return redirect()->route('admin.index')->with('error', 'No se encontró información del docente.');
            }
            
            $asignaciones = Asignacion::with(['gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->where('docente_id', $docente->id)
                ->where('estado', 'activo')
                ->orderBy('gestion_id', 'desc')
                ->get();
            
            return view('admin.calificaciones.index_docente', compact('docente', 'asignaciones'));
        }
        
        if (in_array('ESTUDIANTE', $roles)) {
            // Vista para estudiantes
            $estudiante = Estudiante::where('usuario_id', $user->id)->first();
            
            if (!$estudiante) {
                return redirect()->route('admin.index')->with('error', 'No se encontró información del estudiante.');
            }
            
            $asistencias = Asistencia::with(['asignacion.materia', 'asignacion.docente'])
                ->where('estudiante_id', $estudiante->id)
                ->orderBy('fecha', 'desc')
                ->paginate(15);
            
            return view('admin.asistencias.index_estudiante', compact('estudiante', 'asistencias'));
        }
        
        return redirect()->route('admin.index')->with('error', 'No tiene permisos para acceder a este módulo.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Calificacion $calificacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calificacion $calificacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calificacion $calificacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calificacion $calificacion)
    {
        //
    }
}
