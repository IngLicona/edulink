<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Calificacion;
use App\Models\DetalleCalificacion;
use App\Models\Estudiante;
use App\Models\Matriculacion;
use App\Models\Personal;
use App\Models\Periodo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }

            $roles = $user->roles->pluck('name')->toArray();

            if (in_array('ADMINISTRADOR', $roles) || in_array('DIRECTOR/A GENERAL', $roles) || in_array('SECRETARIO/A', $roles)) {
                // Vista para administradores y directores
                $asignaciones = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                    ->where('estado', 'activo')
                    ->orderBy('gestion_id', 'desc')
                    ->orderBy('nivel_id')
                    ->orderBy('grado_id')
                    ->orderBy('paralelo_id')
                    ->get();

                // Obtener los periodos de la gestión actual
                $periodos = Periodo::whereIn('gestion_id', $asignaciones->pluck('gestion_id')->unique())
                    ->orderBy('nombre')
                    ->get();
                
                return view('admin.calificaciones.index', compact('asignaciones', 'periodos'));
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
                
                // Obtener los periodos de las gestiones asignadas
                $periodos = Periodo::whereIn('gestion_id', $asignaciones->pluck('gestion_id')->unique())
                    ->orderBy('nombre')
                    ->get();
                
                return view('admin.calificaciones.index_docente', compact('docente', 'asignaciones', 'periodos'));
            }
            
            if (in_array('ESTUDIANTE', $roles)) {
                // Vista para estudiantes - mostrar sus calificaciones
                $estudiante = Estudiante::where('usuario_id', $user->id)->first();
                
                if (!$estudiante) {
                    return redirect()->route('admin.index')->with('error', 'No se encontró información del estudiante.');
                }

                // Obtener matriculaciones activas del estudiante
                $matriculaciones = Matriculacion::where('estudiante_id', $estudiante->id)
                    ->where('estado', 'activo')
                    ->with(['asignacion.materia', 'asignacion.docente', 'asignacion.gestion', 
                           'asignacion.nivel', 'asignacion.grado', 'asignacion.paralelo'])
                    ->get();

                // Obtener calificaciones del estudiante
                $calificaciones = [];
                foreach ($matriculaciones as $matriculacion) {
                    $calif = DetalleCalificacion::with(['calificacion.periodo', 'calificacion.asignacion.materia'])
                        ->where('estudiante_id', $estudiante->id)
                        ->whereHas('calificacion.asignacion', function($query) use ($matriculacion) {
                            $query->where('id', $matriculacion->asignacion_id);
                        })
                        ->get();
                        
                    $calificaciones[$matriculacion->asignacion_id] = $calif;
                }
                
                return view('admin.calificaciones.index_estudiante', compact('estudiante', 'matriculaciones', 'calificaciones'));
            }
            
            return redirect()->route('admin.index')->with('error', 'No tiene permisos para acceder a este módulo.');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.index')
                ->with('error', 'Error al cargar las calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($asignacion_id)
    {
        $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacion_id);

        // Obtener estudiantes matriculados en esta asignación
        $estudiantes = Estudiante::whereHas('matriculaciones', function($query) use ($asignacion_id) {
            $query->where('asignacion_id', $asignacion_id)
                  ->where('estado', 'activo');
        })->orderBy('paterno')->orderBy('materno')->orderBy('nombre')->get();

        // Obtener periodos de la gestión
        $periodos = Periodo::where('gestion_id', $asignacion->gestion_id)->get();

        return view('admin.calificaciones.create', compact('asignacion', 'estudiantes', 'periodos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required|exists:asignacions,id',
            'periodo_id' => 'required|exists:periodos,id',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'fecha' => 'required|date',
            'calificaciones' => 'required|array',
            'calificaciones.*' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            // Crear la calificación principal
            $calificacion = Calificacion::create([
                'asignacion_id' => $request->asignacion_id,
                'periodo_id' => $request->periodo_id,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
            ]);

            // Crear los detalles de calificación para cada estudiante
            foreach ($request->calificaciones as $estudiante_id => $nota) {
                DetalleCalificacion::create([
                    'calificacion_id' => $calificacion->id,
                    'estudiante_id' => $estudiante_id,
                    'nota' => $nota
                ]);
            }

            DB::commit();
            return redirect()->route('admin.calificaciones.show_admin', $request->asignacion_id)
                           ->with('success', 'Calificaciones registradas exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al registrar las calificaciones: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified resource - Vista para administradores
     */
    public function show_admin($asignacion_id)
    {
        $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacion_id);

        // Obtener todas las calificaciones de esta asignación
        $calificaciones = Calificacion::with(['periodo', 'detalleCalificaciones.estudiante'])
            ->where('asignacion_id', $asignacion_id)
            ->orderBy('periodo_id')
            ->orderBy('fecha')
            ->get();

        // Obtener estudiantes matriculados
        $estudiantes = Estudiante::whereHas('matriculaciones', function($query) use ($asignacion_id) {
            $query->where('asignacion_id', $asignacion_id)
                  ->where('estado', 'activo');
        })->orderBy('paterno')->orderBy('materno')->orderBy('nombre')->get();

        // Obtener periodos de la gestión para organizar las calificaciones
        $periodos = Periodo::where('gestion_id', $asignacion->gestion_id)->get();

        return view('admin.calificaciones.show_admin', compact('asignacion', 'calificaciones', 'estudiantes', 'periodos'));
    }

    /**
     * Display the specified resource - Vista detallada para estudiante específico
     */
    public function show_estudiante($asignacion_id, $estudiante_id)
    {
        $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->findOrFail($asignacion_id);
            
        $estudiante = Estudiante::findOrFail($estudiante_id);

        // Verificar que el estudiante está matriculado en esta asignación
        $matriculacion = Matriculacion::where('asignacion_id', $asignacion_id)
            ->where('estudiante_id', $estudiante_id)
            ->where('estado', 'activo')
            ->firstOrFail();

        // Obtener calificaciones del estudiante en esta materia
        $calificaciones = DetalleCalificacion::with(['calificacion.periodo'])
            ->where('estudiante_id', $estudiante_id)
            ->whereHas('calificacion', function($query) use ($asignacion_id) {
                $query->where('asignacion_id', $asignacion_id);
            })
            ->orderBy('created_at')
            ->get();

        // Calcular promedio por periodo
        $promediosPorPeriodo = $calificaciones->groupBy('calificacion.periodo_id')
            ->map(function($calif) {
                return round($calif->avg('nota'), 2);
            });

        // Promedio general
        $promedioGeneral = $calificaciones->count() > 0 ? round($calificaciones->avg('nota'), 2) : 0;

        return view('admin.calificaciones.show_estudiante', compact(
            'asignacion', 'estudiante', 'calificaciones', 'promediosPorPeriodo', 'promedioGeneral'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($calificacion_id)
    {
        $calificacion = Calificacion::with(['asignacion', 'periodo', 'detalleCalificaciones.estudiante'])
            ->findOrFail($calificacion_id);

        return view('admin.calificaciones.edit', compact('calificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $calificacion_id)
    {
        $calificacion = Calificacion::findOrFail($calificacion_id);
        
        $request->validate([
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'fecha' => 'required|date',
            'calificaciones' => 'required|array',
            'calificaciones.*' => 'required|numeric|min:0|max:100'
        ]);

        DB::beginTransaction();
        try {
            // Actualizar la calificación principal
            $calificacion->update([
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
            ]);

            // Actualizar los detalles de calificación
            foreach ($request->calificaciones as $estudiante_id => $nota) {
                DetalleCalificacion::where('calificacion_id', $calificacion->id)
                    ->where('estudiante_id', $estudiante_id)
                    ->update(['nota' => $nota]);
            }

            DB::commit();
            return redirect()->route('admin.calificaciones.show_admin', $calificacion->asignacion_id)
                           ->with('success', 'Calificaciones actualizadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar las calificaciones: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($calificacion_id)
    {
        try {
            $calificacion = Calificacion::findOrFail($calificacion_id);
            $asignacion_id = $calificacion->asignacion_id;
            
            // Eliminar detalles primero (por la relación de clave foránea)
            DetalleCalificacion::where('calificacion_id', $calificacion_id)->delete();
            
            // Eliminar la calificación principal
            $calificacion->delete();
            
            return redirect()->route('admin.calificaciones.show_admin', $asignacion_id)
                           ->with('success', 'Calificación eliminada exitosamente.');
                           
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la calificación: ' . $e->getMessage());
        }
    }

    /**
     * Generar reporte de calificaciones por período en PDF
     */
    public function reporte(Request $request, $asignacion_id)
    {
        try {
            $asignacion = Asignacion::with(['docente', 'gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->findOrFail($asignacion_id);

            // Si no es reporte general, validar período
            if (!$request->has('general')) {
                $request->validate([
                    'periodo_id' => 'required|exists:periodos,id'
                ], [
                    'periodo_id.required' => 'Debe seleccionar un período',
                    'periodo_id.exists' => 'El período seleccionado no es válido'
                ]);

                $periodo = Periodo::findOrFail($request->periodo_id);
                
                $calificaciones = Calificacion::with(['periodo', 'detalleCalificaciones.estudiante'])
                    ->where('asignacion_id', $asignacion_id)
                    ->where('periodo_id', $request->periodo_id)
                    ->orderBy('fecha')
                    ->get();
            } else {
                // Para reporte general, obtener todas las calificaciones
                $calificaciones = Calificacion::with(['periodo', 'detalleCalificaciones.estudiante'])
                    ->where('asignacion_id', $asignacion_id)
                    ->orderBy('periodo_id')
                    ->orderBy('fecha')
                    ->get();
            }

            $estudiantes = Estudiante::whereHas('matriculaciones', function($query) use ($asignacion_id) {
                $query->where('asignacion_id', $asignacion_id)
                      ->where('estado', 'activo');
            })->orderBy('paterno')->orderBy('materno')->orderBy('nombre')->get();

            $periodos = Periodo::where('gestion_id', $asignacion->gestion_id)
                              ->orderBy('nombre')
                              ->get();
            
            // Obtener la configuración
            $configuracion = \App\Models\Configuracion::first();

            $data = [
                'asignacion' => $asignacion,
                'calificaciones' => $calificaciones,
                'estudiantes' => $estudiantes,
                'periodos' => $periodos,
                'configuracion' => $configuracion,
                'es_general' => $request->has('general')
            ];

            // Generar el PDF con DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.calificaciones.reporte', $data);
            
            // Configurar el PDF
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            // Generar nombre del archivo
            $nombreArchivo = 'calificaciones_' . 
                           $asignacion->materia->nombre . '_' . 
                           $asignacion->grado->nombre . $asignacion->paralelo->nombre . '_' .
                           ($request->has('general') ? 'general_' : 'periodo_' . $request->periodo_id . '_') .
                           $asignacion->gestion->nombre . '.pdf';

            // Descargar el PDF
            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }
}