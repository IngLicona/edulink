<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Personal;
use App\Models\Estudiante;
use App\Models\Matriculacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Asistencia;


class AsistenciaController extends Controller
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
            $docente = Personal::where('usuario_id', $user->id)->first();
            
            if (!$docente) {
                return redirect()->route('admin.index')->with('error', 'No se encontró información del docente.');
            }
            
            $asignaciones = Asignacion::with(['gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->where('docente_id', $docente->id)
                ->where('estado', 'activo')
                ->orderBy('gestion_id', 'desc')
                ->get();
            
            return view('admin.asistencias.index_docente', compact('docente', 'asignaciones'));
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
    public function create($asignacionId)
    {
    $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacionId);
        
        // Verificar que el usuario puede registrar asistencia para esta asignación
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('DOCENTE', $roles)) {
            $docente = Personal::where('usuario_id', $user->id)->first();
            if (!$docente || $asignacion->docente_id !== $docente->id) {
                return redirect()->route('admin.asistencias.index')
                    ->with('error', 'No tiene permisos para registrar asistencia en esta asignación.');
            }
        }
        
        // Obtener estudiantes matriculados en esta asignación
        $estudiantes = Matriculacion::with('estudiante')
            ->where('gestion_id', $asignacion->gestion_id)
            ->where('nivel_id', $asignacion->nivel_id)
            ->where('grado_id', $asignacion->grado_id)
            ->where('paralelo_id', $asignacion->paralelo_id)
            ->where('turno_id', $asignacion->turno_id)
            ->where('estado', 'activo')
            ->get()
            ->pluck('estudiante')
            ->sortBy(function($estudiante) {
                return $estudiante->paterno . ' ' . $estudiante->materno . ' ' . $estudiante->nombre;
            });

        $fecha = request('fecha', date('Y-m-d'));
        
        // Verificar si ya existe asistencia para esta fecha
        $asistenciasExistentes = Asistencia::where('asignacion_id', $asignacionId)
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('estudiante_id');

        return view('admin.asistencias.create', compact('asignacion', 'estudiantes', 'fecha', 'asistenciasExistentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required|exists:asignacions,id',
            'fecha' => 'required|date|before_or_equal:today',
            'asistencias' => 'required|array|min:1',
            'asistencias.*.estudiante_id' => 'required|exists:estudiantes,id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tarde,justificado',
            'asistencias.*.observacion' => 'nullable|string|max:500'
        ], [
            'fecha.before_or_equal' => 'La fecha no puede ser posterior al día de hoy.',
            'asistencias.required' => 'Debe seleccionar al menos un estudiante.',
            'asistencias.*.estudiante_id.required' => 'El estudiante es requerido.',
            'asistencias.*.estado.required' => 'El estado de asistencia es requerido.',
        ]);

        // Verificar que la asignación existe y está activa
        $asignacion = Asignacion::where('id', $request->asignacion_id)
            ->where('estado', 'activo')
            ->first();
            
        if (!$asignacion) {
            return back()->with('error', 'La asignación no existe o no está activa.');
        }

        // Verificar permisos del docente
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('DOCENTE', $roles)) {
            $docente = Personal::where('usuario_id', $user->id)->first();
            if (!$docente || $asignacion->docente_id !== $docente->id) {
                return back()->with('error', 'No tiene permisos para registrar asistencia en esta asignación.');
            }
        }

        DB::beginTransaction();
        
        try {
            // Eliminar asistencias existentes para esta asignación y fecha
            Asistencia::where('asignacion_id', $request->asignacion_id)
                ->where('fecha', $request->fecha)
                ->delete();

            // Crear nuevas asistencias
            $asistenciasCreadas = 0;
            foreach ($request->asistencias as $asistenciaData) {
                Asistencia::create([
                    'asignacion_id' => $request->asignacion_id,
                    'estudiante_id' => $asistenciaData['estudiante_id'],
                    'fecha' => $request->fecha,
                    'estado' => $asistenciaData['estado'],
                    'observacion' => $asistenciaData['observacion'] ?? null
                ]);
                $asistenciasCreadas++;
            }

            DB::commit();
            
            return redirect()->route('admin.asistencias.index')
                ->with('success', "Se registraron correctamente {$asistenciasCreadas} asistencias para el " . Carbon::parse($request->fecha)->format('d/m/Y') . ".");
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al registrar asistencias: ' . $e->getMessage());
            return back()->with('error', 'Error al registrar las asistencias. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $asignacionId)
    {
    $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacionId);
        
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Validar fechas
        if (Carbon::parse($fechaInicio)->gt(Carbon::parse($fechaFin))) {
            return back()->with('error', 'La fecha de inicio no puede ser mayor a la fecha fin.');
        }
        
        // Obtener asistencias en el rango de fechas
        $asistencias = Asistencia::with('estudiante')
            ->where('asignacion_id', $asignacionId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->get();
        
        // Agrupar por estudiante
        $asistenciasPorEstudiante = $asistencias->groupBy('estudiante_id');
        
        // Obtener todos los estudiantes matriculados (incluso si no tienen asistencias)
        $estudiantes = Matriculacion::with('estudiante')
            ->where('gestion_id', $asignacion->gestion_id)
            ->where('nivel_id', $asignacion->nivel_id)
            ->where('grado_id', $asignacion->grado_id)
            ->where('paralelo_id', $asignacion->paralelo_id)
            ->where('turno_id', $asignacion->turno_id)
            ->where('estado', 'activo')
            ->get()
            ->pluck('estudiante')
            ->keyBy('id');
        
        return view('admin.asistencias.show', compact('asignacion', 'asistenciasPorEstudiante', 'estudiantes', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        $asistencia->load(['asignacion', 'estudiante']);
        
        // Verificar permisos
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('DOCENTE', $roles)) {
            $docente = Personal::where('usuario_id', $user->id)->first();
            if (!$docente || $asistencia->asignacion->docente_id !== $docente->id) {
                return redirect()->route('admin.asistencias.index')
                    ->with('error', 'No tiene permisos para editar esta asistencia.');
            }
        }
        
        return view('admin.asistencias.edit', compact('asistencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        $request->validate([
            'estado' => 'required|in:presente,ausente,tarde,justificado',
            'observacion' => 'nullable|string|max:500'
        ]);

        // Verificar permisos
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('DOCENTE', $roles)) {
            $docente = Personal::where('usuario_id', $user->id)->first();
            if (!$docente || $asistencia->asignacion->docente_id !== $docente->id) {
                return redirect()->route('admin.asistencias.index')
                    ->with('error', 'No tiene permisos para editar esta asistencia.');
            }
        }

    $asistencia->update($request->only('estado', 'observacion'));

        return redirect()->route('admin.asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        // Verificar permisos
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('DOCENTE', $roles)) {
            $docente = Personal::where('usuario_id', $user->id)->first();
            if (!$docente || $asistencia->asignacion->docente_id !== $docente->id) {
                return redirect()->route('admin.asistencias.index')
                    ->with('error', 'No tiene permisos para eliminar esta asistencia.');
            }
        }
        
        $asistencia->delete();
        
        return redirect()->route('admin.asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }

    /**
     * Generar reporte de asistencias
     */
    public function reporte(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required|exists:asignacions,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);
        
        $asignacionId = $request->get('asignacion_id');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        
    $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacionId);
        
        $asistencias = Asistencia::with('estudiante')
            ->where('asignacion_id', $asignacionId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->get();
        
        // Agrupar por estudiante y calcular estadísticas
        $estadisticas = $asistencias->groupBy('estudiante_id')->map(function($asistenciasEstudiante) {
            $total = $asistenciasEstudiante->count();
            $presente = $asistenciasEstudiante->where('estado', 'presente')->count();
            $ausente = $asistenciasEstudiante->where('estado', 'ausente')->count();
            $tarde = $asistenciasEstudiante->where('estado', 'tarde')->count();
            $justificado = $asistenciasEstudiante->where('estado', 'justificado')->count();
            
            return [
                'estudiante' => $asistenciasEstudiante->first()->estudiante,
                'total' => $total,
                'presente' => $presente,
                'ausente' => $ausente,
                'tarde' => $tarde,
                'justificado' => $justificado,
                'porcentaje_asistencia' => $total > 0 ? round(($presente + $tarde) / $total * 100, 2) : 0
            ];
        });
        
        return view('admin.asistencias.reporte', compact('asignacion', 'estadisticas', 'fechaInicio', 'fechaFin'));
    }
}