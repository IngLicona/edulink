<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Personal;
use App\Models\Gestion;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Paralelo;
use App\Models\Materia;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaciones = Asignacion::with([
            'docente',
            'gestion',
            'nivel',
            'grado',
            'paralelo',
            'materia',
            'turno'
        ])->orderBy('created_at', 'desc')->get();

        $gestiones = Gestion::all();
        $docentes = Personal::where('tipo', 'docente')->get();
        $niveles = Nivel::all();
        $materias = Materia::all();
        $turnos = Turno::all();

        return view('admin.asignaciones.index', compact(
            'asignaciones',
            'gestiones',
            'docentes',
            'niveles',
            'materias',
            'turnos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gestiones = Gestion::all();
        $docentes = Personal::where('tipo', 'docente')->get();
        $niveles = Nivel::all();
        $materias = Materia::all();
        $turnos = Turno::all();

        return view('admin.asignaciones.create', compact(
            'gestiones',
            'docentes',
            'niveles',
            'materias',
            'turnos'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'docente_id' => 'required|exists:personals,id',
            'gestion_id' => 'required|exists:gestions,id',
            'nivel_id' => 'required|exists:nivels,id',
            'grado_id' => 'required|exists:grados,id',
            'paralelo_id' => 'required|exists:paralelos,id',
            'materia_id' => 'required|exists:materias,id',
            'turno_id' => 'required|exists:turnos,id',
            'estado' => 'required|in:activo,inactivo',
            'fecha_asignacion' => 'required|date'
        ]);

        // Verificar que no exista una asignación duplicada
        $existeAsignacion = Asignacion::where([
            ['docente_id', $request->docente_id],
            ['gestion_id', $request->gestion_id],
            ['nivel_id', $request->nivel_id],
            ['grado_id', $request->grado_id],
            ['paralelo_id', $request->paralelo_id],
            ['materia_id', $request->materia_id],
            ['turno_id', $request->turno_id]
        ])->exists();

        if ($existeAsignacion) {
            return back()->withErrors(['error' => 'Ya existe una asignación similar para este docente.']);
        }

        try {
            Asignacion::create($request->all());
            return redirect()->route('admin.asignaciones.index')
                ->with('success', 'Asignación creada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignacion $asignacion)
    {
        $asignacion->load([
            'docente',
            'gestion',
            'nivel',
            'grado',
            'paralelo',
            'materia',
            'turno'
        ]);

        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignacion $asignacion)
    {
        $docentes = Personal::where('tipo', 'docente')->get();
        $gestiones = Gestion::all();
        $niveles = Nivel::all();
        $grados = Grado::all();
        $paralelos = Paralelo::all();
        $materias = Materia::all();
        $turnos = Turno::all();

        return view('admin.asignaciones.edit', compact(
            'asignacion',
            'docentes',
            'gestiones',
            'niveles',
            'grados',
            'paralelos',
            'materias',
            'turnos'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignacion $asignacion)
    {
        $request->validate([
            'docente_id' => 'required|exists:personals,id',
            'gestion_id' => 'required|exists:gestions,id',
            'nivel_id' => 'required|exists:nivels,id',
            'grado_id' => 'required|exists:grados,id',
            'paralelo_id' => 'required|exists:paralelos,id',
            'materia_id' => 'required|exists:materias,id',
            'turno_id' => 'required|exists:turnos,id',
            'estado' => 'required|in:activo,inactivo',
            'fecha_asignacion' => 'required|date'
        ]);

        // Verificar que no exista una asignación duplicada (excluyendo la actual)
        $existeAsignacion = Asignacion::where([
            ['docente_id', $request->docente_id],
            ['gestion_id', $request->gestion_id],
            ['nivel_id', $request->nivel_id],
            ['grado_id', $request->grado_id],
            ['paralelo_id', $request->paralelo_id],
            ['materia_id', $request->materia_id],
            ['turno_id', $request->turno_id]
        ])->where('id', '!=', $asignacion->id)->exists();

        if ($existeAsignacion) {
            return back()->withErrors(['error' => 'Ya existe una asignación similar para este docente.']);
        }

        try {
            $asignacion->update($request->all());
            return redirect()->route('admin.asignaciones.index')
                ->with('success', 'Asignación actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignacion $asignacion)
    {
        try {
            $asignacion->delete();
            return redirect()->route('admin.asignaciones.index')
                ->with('success', 'Asignación eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Métodos AJAX para carga dinámica
     */
    public function getGradosByNivel(Request $request)
    {
        try {
            $grados = Grado::where('nivel_id', $request->nivel_id)->get(['id', 'nombre']);
            return response()->json($grados);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar grados'], 500);
        }
    }

    public function getParalelosByGrado(Request $request)
    {
        try {
            $paralelos = Paralelo::where('grado_id', $request->grado_id)->get(['id', 'nombre']);
            return response()->json($paralelos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar paralelos'], 500);
        }
    }

    public function getDocentesDisponibles(Request $request)
    {
        try {
            // Obtener docentes que no tengan asignación en el mismo horario
            $docentesOcupados = Asignacion::where([
                ['gestion_id', $request->gestion_id],
                ['nivel_id', $request->nivel_id],
                ['grado_id', $request->grado_id],
                ['paralelo_id', $request->paralelo_id],
                ['turno_id', $request->turno_id],
                ['estado', 'activo']
            ])->pluck('docente_id')->toArray();

            $docentes = Personal::where('tipo', 'docente')
                ->whereNotIn('id', $docentesOcupados)
                ->get(['id', 'nombre', 'paterno', 'materno', 'ci', 'profesion']);

            return response()->json($docentes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar docentes disponibles'], 500);
        }
    }

    /**
     * Verificar conflictos de horario
     */
    public function verificarConflicto(Request $request)
    {
        try {
            $conflicto = Asignacion::where([
                ['docente_id', $request->docente_id],
                ['gestion_id', $request->gestion_id],
                ['turno_id', $request->turno_id],
                ['estado', 'activo']
            ]);

            if ($request->has('asignacion_id') && $request->asignacion_id) {
                $conflicto->where('id', '!=', $request->asignacion_id);
            }

            $existeConflicto = $conflicto->exists();

            return response()->json([
                'conflicto' => $existeConflicto,
                'mensaje' => $existeConflicto ? 'El docente ya tiene una asignación en este turno y gestión.' : 'Sin conflictos'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar conflictos'], 500);
        }
    }

    /**
     * Obtener información detallada del docente
     */
    public function getDocenteInfo(Request $request)
    {
        try {
            $docente = Personal::with('formaciones')
                ->where('id', $request->docente_id)
                ->where('tipo', 'docente')
                ->first();

            if (!$docente) {
                return response()->json(['error' => 'Docente no encontrado'], 404);
            }

            return response()->json([
                'docente' => $docente,
                'formaciones' => $docente->formaciones
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener información del docente'], 500);
        }
    }

    /**
     * Reportes y estadísticas
     */
    public function getAsignacionesPorDocente(Request $request)
    {
        try {
            $asignaciones = Asignacion::with(['gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->where('docente_id', $request->docente_id)
                ->where('estado', 'activo')
                ->get();

            return response()->json($asignaciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener asignaciones del docente'], 500);
        }
    }

    public function getAsignacionesPorGestion(Request $request)
    {
        try {
            $asignaciones = Asignacion::with(['docente', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
                ->where('gestion_id', $request->gestion_id)
                ->where('estado', 'activo')
                ->get();

            return response()->json($asignaciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener asignaciones de la gestión'], 500);
        }
    }

    /**
     * Validar disponibilidad de aula
     */
    public function validarDisponibilidadAula(Request $request)
    {
        try {
            $ocupado = Asignacion::where([
                ['gestion_id', $request->gestion_id],
                ['nivel_id', $request->nivel_id],
                ['grado_id', $request->grado_id],
                ['paralelo_id', $request->paralelo_id],
                ['turno_id', $request->turno_id],
                ['estado', 'activo']
            ]);

            if ($request->has('asignacion_id') && $request->asignacion_id) {
                $ocupado->where('id', '!=', $request->asignacion_id);
            }

            $aulaOcupada = $ocupado->exists();

            return response()->json([
                'ocupado' => $aulaOcupada,
                'mensaje' => $aulaOcupada ? 'El aula ya está ocupada en este horario.' : 'Aula disponible'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar disponibilidad'], 500);
        }
    }
}