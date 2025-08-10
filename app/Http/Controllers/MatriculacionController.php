<?php

namespace App\Http\Controllers;

use App\Models\Matriculacion;
use App\Models\Estudiante;
use App\Models\Turno;
use App\Models\Gestion;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Paralelo;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MatriculacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $matriculaciones = Matriculacion::withAllRelations()
                ->orderBy('fecha_matriculacion', 'desc')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // Si hay error con la consulta, mostrar vista con colecciones vacías
            return view('admin.matriculaciones.index', [
                'matriculaciones' => collect(),
                'estudiantes' => collect(),
                'turnos' => collect(),
                'gestiones' => collect(),
                'niveles' => collect(),
                'grados' => collect(),
                'paralelos' => collect(),
                'error_tabla' => 'Error en la consulta: ' . $e->getMessage()
            ]);
        }
        
        $estudiantes = Estudiante::where('estado', 'activo')->with('ppff')->get();
        $turnos = Turno::all();
        $gestiones = Gestion::all();
        $niveles = Nivel::all();
        $grados = Grado::all();
        $paralelos = Paralelo::all();
        
        return view('admin.matriculaciones.index', compact(
            'matriculaciones',
            'estudiantes',
            'turnos',
            'gestiones',
            'niveles',
            'grados',
            'paralelos'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'turno_id' => 'required|exists:turnos,id',
            'gestion_id' => 'required|exists:gestions,id',
            'nivel_id' => 'required|exists:nivels,id',
            'grado_id' => 'required|exists:grados,id',
            'paralelo_id' => 'required|exists:paralelos,id',
            'fecha_matriculacion' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'estudiante_id.required' => 'Debe seleccionar un estudiante',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe',
            'turno_id.required' => 'Debe seleccionar un turno',
            'turno_id.exists' => 'El turno seleccionado no existe',
            'gestion_id.required' => 'Debe seleccionar una gestión',
            'gestion_id.exists' => 'La gestión seleccionada no existe',
            'nivel_id.required' => 'Debe seleccionar un nivel',
            'nivel_id.exists' => 'El nivel seleccionado no existe',
            'grado_id.required' => 'Debe seleccionar un grado',
            'grado_id.exists' => 'El grado seleccionado no existe',
            'paralelo_id.required' => 'Debe seleccionar un paralelo',
            'paralelo_id.exists' => 'El paralelo seleccionado no existe',
            'fecha_matriculacion.required' => 'La fecha de matriculación es obligatoria',
            'fecha_matriculacion.date' => 'La fecha de matriculación debe ser una fecha válida',
            'estado.in' => 'El estado debe ser activo o inactivo',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

            // Verificar que el estudiante no esté ya matriculado en la misma gestión
            $matriculacionExistente = Matriculacion::where('estudiante_id', $request->estudiante_id)
                ->where('gestion_id', $request->gestion_id)
                ->first();

            if ($matriculacionExistente) {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'El estudiante ya está matriculado en esta gestión')
                    ->withInput();
            }

            // Verificar que el estudiante esté activo
            $estudiante = Estudiante::find($request->estudiante_id);
            if ($estudiante->estado !== 'activo') {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'No se puede matricular un estudiante inactivo')
                    ->withInput();
            }

            // Verificar relaciones jerárquicas
            $this->validateHierarchy($request);

            Matriculacion::create($request->all());

            DB::commit();

            return redirect()->route('admin.matriculaciones.index')
                ->with('success', 'Matriculación registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar matriculación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Matriculacion $matriculacion)
    {
        $matriculacion->load([
            'estudiante.ppff',
            'turno',
            'gestion',
            'nivel',
            'grado',
            'paralelo'
        ]);
        
        return view('admin.matriculaciones.show', compact('matriculacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Matriculacion $matriculacion)
    {
        $validator = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'turno_id' => 'required|exists:turnos,id',
            'gestion_id' => 'required|exists:gestions,id',
            'nivel_id' => 'required|exists:nivels,id',
            'grado_id' => 'required|exists:grados,id',
            'paralelo_id' => 'required|exists:paralelos,id',
            'fecha_matriculacion' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'estudiante_id.required' => 'Debe seleccionar un estudiante',
            'turno_id.required' => 'Debe seleccionar un turno',
            'gestion_id.required' => 'Debe seleccionar una gestión',
            'nivel_id.required' => 'Debe seleccionar un nivel',
            'grado_id.required' => 'Debe seleccionar un grado',
            'paralelo_id.required' => 'Debe seleccionar un paralelo',
            'fecha_matriculacion.required' => 'La fecha de matriculación es obligatoria',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

            // Verificar que no exista otra matriculación con los mismos datos (excepto la actual)
            $matriculacionExistente = Matriculacion::where('estudiante_id', $request->estudiante_id)
                ->where('gestion_id', $request->gestion_id)
                ->where('id', '!=', $matriculacion->id)
                ->first();

            if ($matriculacionExistente) {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'El estudiante ya está matriculado en esta gestión')
                    ->withInput();
            }

            // Verificar relaciones jerárquicas
            $this->validateHierarchy($request);

            $matriculacion->update($request->all());

            DB::commit();

            return redirect()->route('admin.matriculaciones.index')
                ->with('success', 'Matriculación actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar matriculación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matriculacion $matriculacion)
    {
        try {
            DB::beginTransaction();

            $matriculacion->delete();

            DB::commit();

            return redirect()->route('admin.matriculaciones.index')
                ->with('success', 'Matriculación eliminada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar matriculación: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF de matrícula - MÉTODO CORREGIDO COMPLETAMENTE
     */
    public function generarPDF(Matriculacion $matriculacion)
    {
        // Cargar todas las relaciones necesarias
        $matriculacion->load([
            'estudiante.ppff',
            'turno',
            'gestion',
            'nivel',
            'grado',
            'paralelo'
        ]);

        // Obtener configuración de la institución
        $configuracion = Configuracion::first();

        // Procesar rutas de imágenes para PDF
        $logoPath = null;
        $fotoEstudiantePath = null;

        // ========= PROCESAMIENTO DEL LOGO - COMPLETAMENTE CORREGIDO =========
        if ($configuracion && !empty($configuracion->logotipo)) {
            Log::info('=== PROCESAMIENTO DEL LOGO INICIADO ===');
            Log::info('Campo logotipo de configuración: ' . $configuracion->logotipo);

            // Intentar múltiples rutas posibles para el logo
            $posibleRutas = [
                // Ruta directa en uploads/logos/
                public_path('uploads/logos/' . $configuracion->logotipo),
                // Ruta en storage/app/public/logos/
                storage_path('app/public/logos/' . $configuracion->logotipo),
                // Ruta en storage/app/public/ (por si se guardó sin subcarpeta)
                storage_path('app/public/' . $configuracion->logotipo),
                // Ruta relativa desde public
                public_path($configuracion->logotipo),
                // Ruta si se guardó con prefijo logos/
                public_path('uploads/' . $configuracion->logotipo),
                // Ruta en storage con el prefijo completo
                storage_path('app/public/' . ltrim($configuracion->logotipo, '/')),
            ];

            foreach ($posibleRutas as $index => $rutaPosible) {
                Log::info("Probando ruta #{$index}: {$rutaPosible}");
                
                if (file_exists($rutaPosible)) {
                    Log::info("✓ Archivo encontrado en: {$rutaPosible}");
                    
                    try {
                        // Verificar que sea un archivo de imagen válido
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        $extension = strtolower(pathinfo($rutaPosible, PATHINFO_EXTENSION));
                        
                        Log::info('Extensión del archivo: ' . $extension);
                        
                        if (in_array($extension, $allowedExtensions)) {
                            // Intentar obtener información de la imagen
                            $imageInfo = @getimagesize($rutaPosible);
                            
                            if ($imageInfo !== false) {
                                // Leer el archivo y convertir a base64
                                $fileContent = file_get_contents($rutaPosible);
                                
                                if ($fileContent !== false) {
                                    $logoMimeType = $imageInfo['mime'];
                                    $logoBase64 = base64_encode($fileContent);
                                    $logoPath = 'data:' . $logoMimeType . ';base64,' . $logoBase64;
                                    
                                    Log::info('✓ Logo procesado exitosamente desde: ' . $rutaPosible);
                                    Log::info('Tipo MIME: ' . $logoMimeType);
                                    Log::info('Tamaño de imagen: ' . $imageInfo[0] . 'x' . $imageInfo[1]);
                                    Log::info('Tamaño base64: ' . strlen($logoBase64) . ' caracteres');
                                    break; // Salir del bucle si se procesó exitosamente
                                } else {
                                    Log::error('No se pudo leer el contenido del archivo del logo desde: ' . $rutaPosible);
                                }
                            } else {
                                Log::error('getimagesize() falló para: ' . $rutaPosible . ' - archivo corrupto o no es imagen válida');
                            }
                        } else {
                            Log::error('Extensión de archivo no permitida para logo: ' . $extension . ' en ' . $rutaPosible);
                        }
                    } catch (\Exception $e) {
                        Log::error('Excepción al procesar logo desde ' . $rutaPosible . ': ' . $e->getMessage());
                    }
                } else {
                    Log::info("✗ Archivo NO encontrado en: {$rutaPosible}");
                }
            }

            // Si no se encontró en ninguna ruta, mostrar información de directorios
            if (!$logoPath) {
                Log::error('=== LOGO NO ENCONTRADO EN NINGUNA RUTA ===');
                
                // Verificar qué directorios existen y qué contienen
                $directoriosAVerificar = [
                    'public/uploads/logos/' => public_path('uploads/logos/'),
                    'storage/app/public/logos/' => storage_path('app/public/logos/'),
                    'storage/app/public/' => storage_path('app/public/'),
                ];

                foreach ($directoriosAVerificar as $nombre => $directorio) {
                    Log::info("=== VERIFICANDO DIRECTORIO: {$nombre} ===");
                    if (is_dir($directorio)) {
                        Log::info("✓ Directorio existe: {$directorio}");
                        $archivos = scandir($directorio);
                        $archivos = array_diff($archivos, ['.', '..']);
                        Log::info('Archivos encontrados: ' . json_encode(array_values($archivos)));
                        
                        // Verificar permisos
                        Log::info('¿Es legible?: ' . (is_readable($directorio) ? 'SÍ' : 'NO'));
                        Log::info('¿Es escribible?: ' . (is_writable($directorio) ? 'SÍ' : 'NO'));
                    } else {
                        Log::info("✗ Directorio NO existe: {$directorio}");
                    }
                }
            }
        } else {
            Log::info('=== NO HAY CONFIGURACIÓN DE LOGO ===');
            if ($configuracion) {
                Log::info('Configuración existe, pero campo logotipo está vacío o es null');
                Log::info('Valor del campo logotipo: ' . var_export($configuracion->logotipo, true));
            } else {
                Log::info('No existe registro de configuración en la base de datos');
            }
        }

        // ========= PROCESAMIENTO DE FOTO DEL ESTUDIANTE (sin cambios, ya funciona) =========
        if ($matriculacion->estudiante->foto && !empty($matriculacion->estudiante->foto)) {
            $fotoFullPath = public_path('uploads/estudiantes/fotos/' . $matriculacion->estudiante->foto);
            
            Log::info('=== PROCESAMIENTO DE FOTO ESTUDIANTE ===');
            Log::info('Campo foto del estudiante: ' . $matriculacion->estudiante->foto);
            Log::info('Ruta completa de la foto: ' . $fotoFullPath);
            
            if (file_exists($fotoFullPath)) {
                try {
                    // Verificar extensión
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    $extension = strtolower(pathinfo($fotoFullPath, PATHINFO_EXTENSION));
                    
                    if (in_array($extension, $allowedExtensions)) {
                        // Intentar obtener información de la imagen
                        $imageInfo = @getimagesize($fotoFullPath);
                        
                        if ($imageInfo !== false) {
                            $fileContent = file_get_contents($fotoFullPath);
                            
                            if ($fileContent !== false) {
                                $fotoMimeType = $imageInfo['mime'];
                                $fotoBase64 = base64_encode($fileContent);
                                $fotoEstudiantePath = 'data:' . $fotoMimeType . ';base64,' . $fotoBase64;
                                
                                Log::info('Foto del estudiante procesada exitosamente');
                                Log::info('Tipo MIME: ' . $fotoMimeType);
                            } else {
                                Log::error('No se pudo leer el contenido de la foto del estudiante');
                            }
                        } else {
                            Log::error('getimagesize() falló para la foto del estudiante');
                        }
                    } else {
                        Log::error('Extensión no permitida para foto del estudiante: ' . $extension);
                    }
                } catch (\Exception $e) {
                    Log::error('Excepción al procesar foto del estudiante: ' . $e->getMessage());
                }
            } else {
                Log::error('Archivo de foto del estudiante no encontrado: ' . $fotoFullPath);
            }
        } else {
            Log::info('No hay foto del estudiante configurada');
        }

        // Preparar datos para la vista
        $data = [
            'matriculacion' => $matriculacion,
            'configuracion' => $configuracion,
            'logoPath' => $logoPath,
            'fotoEstudiantePath' => $fotoEstudiantePath,
            'fecha_generacion' => now()->format('d/m/Y H:i:s')
        ];

        // Log final de verificación
        Log::info('=== DATOS FINALES PARA PDF ===');
        Log::info('Configuración nombre: ' . ($configuracion->nombre ?? 'N/A'));
        Log::info('Logo procesado: ' . ($logoPath ? 'SÍ (longitud: ' . strlen($logoPath) . ' caracteres)' : 'NO'));
        Log::info('Foto estudiante procesada: ' . ($fotoEstudiantePath ? 'SÍ' : 'NO'));

        try {
            // Generar PDF usando DomPDF
            $pdf = Pdf::loadView('admin.matriculaciones.pdf', $data);
            
            // Configurar el PDF
            $pdf->setPaper('A4', 'portrait');
            
            $nombreArchivo = 'matricula_' . 
                            str_replace(' ', '_', $matriculacion->estudiante->nombre) . '_' . 
                            str_replace(' ', '_', $matriculacion->estudiante->paterno) . '_' .
                            $matriculacion->gestion->nombre . '.pdf';
            
            Log::info('PDF generado exitosamente: ' . $nombreArchivo);
            
            return $pdf->download($nombreArchivo);
            
        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Validar la jerarquía nivel -> grado -> paralelo
     */
    private function validateHierarchy(Request $request)
    {
        // Verificar que el grado pertenezca al nivel seleccionado
        $grado = Grado::find($request->grado_id);
        if (!$grado || $grado->nivel_id != $request->nivel_id) {
            throw new \Exception('El grado seleccionado no pertenece al nivel indicado');
        }

        // Verificar que el paralelo pertenezca al grado seleccionado
        $paralelo = Paralelo::find($request->paralelo_id);
        if (!$paralelo || $paralelo->grado_id != $request->grado_id) {
            throw new \Exception('El paralelo seleccionado no pertenece al grado indicado');
        }
    }

    /**
     * Get grados by nivel_id for AJAX requests
     */
    public function getGradosByNivel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nivel_id' => 'required|exists:nivels,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Nivel inválido'], 400);
        }

        $grados = Grado::where('nivel_id', $request->nivel_id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
            
        return response()->json($grados);
    }

    /**
     * Get paralelos by grado_id for AJAX requests
     */
    public function getParalelosByGrado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grado_id' => 'required|exists:grados,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Grado inválido'], 400);
        }

        $paralelos = Paralelo::where('grado_id', $request->grado_id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
            
        return response()->json($paralelos);
    }

    /**
     * Get estudiantes no matriculados en una gestión específica
     */
    public function getEstudiantesDisponibles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gestion_id' => 'required|exists:gestions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Gestión inválida'], 400);
        }

        $gestionId = $request->gestion_id;
        $estudiantesMatriculados = Matriculacion::where('gestion_id', $gestionId)
            ->pluck('estudiante_id')
            ->toArray();

        $estudiantes = Estudiante::where('estado', 'activo')
            ->whereNotIn('id', $estudiantesMatriculados)
            ->with('ppff')
            ->orderBy('nombre')
            ->orderBy('paterno')
            ->get();

        return response()->json($estudiantes->map(function ($estudiante) {
            return [
                'id' => $estudiante->id,
                'text' => $estudiante->nombre_completo . ' - CI: ' . $estudiante->ci,
                'nombre_completo' => $estudiante->nombre_completo,
                'ci' => $estudiante->ci,
                'ppff' => $estudiante->ppff ? $estudiante->ppff->nombre_completo : 'Sin PPFF',
                'ppff_telefono' => $estudiante->ppff ? $estudiante->ppff->telefono : null
            ];
        }));
    }

    /**
     * Obtener estadísticas de matriculaciones
     */
    public function getEstadisticas(Request $request)
    {
        $gestionId = $request->get('gestion_id');
        
        $query = Matriculacion::query();
        
        if ($gestionId) {
            $query->where('gestion_id', $gestionId);
        }

        $totalMatriculaciones = $query->count();
        $matriculacionesActivas = $query->where('estado', 'activo')->count();
        $matriculacionesInactivas = $query->where('estado', 'inactivo')->count();

        // Estadísticas por nivel
        $porNivel = $query->with('nivel')
            ->selectRaw('nivel_id, count(*) as total')
            ->groupBy('nivel_id')
            ->get()
            ->map(function($item) {
                return [
                    'nivel' => $item->nivel->nombre ?? 'Sin nivel',
                    'total' => $item->total
                ];
            });

        // Estadísticas por turno
        $porTurno = $query->with('turno')
            ->selectRaw('turno_id, count(*) as total')
            ->groupBy('turno_id')
            ->get()
            ->map(function($item) {
                return [
                    'turno' => $item->turno->nombre ?? 'Sin turno',
                    'total' => $item->total
                ];
            });

        return response()->json([
            'total_matriculaciones' => $totalMatriculaciones,
            'activas' => $matriculacionesActivas,
            'inactivas' => $matriculacionesInactivas,
            'por_nivel' => $porNivel,
            'por_turno' => $porTurno
        ]);
    }

    /**
     * Cambiar estado de matriculación
     */
    public function cambiarEstado(Request $request, Matriculacion $matriculacion)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:activo,inactivo'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Estado inválido'], 400);
        }

        try {
            $matriculacion->update(['estado' => $request->estado]);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'nuevo_estado' => $request->estado
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cambiar estado: ' . $e->getMessage()
            ], 500);
        }
    }
}