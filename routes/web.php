<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index.home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

//rutas para la configuracion del sisgema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');
Route::POST('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('admin.configuracion.store')->middleware('auth');

//Rutas para las gestiones del sistema
Route::get('/admin/gestiones', [App\Http\Controllers\GestionController::class, 'index'])->name('admin.gestiones.index')->middleware('auth');
Route::get('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'create'])->name('admin.gestiones.create')->middleware('auth');
Route::POST('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'store'])->name('admin.gestiones.store')->middleware('auth');
Route::get('/admin/gestiones/{id}/edit', [App\Http\Controllers\GestionController::class, 'edit'])->name('admin.gestiones.edit')->middleware('auth');
Route::put('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'update'])->name('admin.gestiones.update')->middleware('auth');
Route::delete('/admin/gestiones/{gestion}', [\App\Http\Controllers\GestionController::class, 'destroy']);

//Rutas para los Periodos
Route::get('/admin/periodos', [App\Http\Controllers\PeriodoController::class, 'index'])->name('admin.periodos.index')->middleware('auth');
Route::POST('/admin/periodos/create', [App\Http\Controllers\PeriodoController::class, 'store'])->name('admin.periodos.store')->middleware('auth');
Route::put('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'update'])->name('admin.periodos.update')->middleware('auth');
Route::delete('/admin/periodos/{gestion}', [\App\Http\Controllers\PeriodoController::class, 'destroy']);

//Rutas para los Niveles
Route::get('/admin/niveles', [App\Http\Controllers\NivelController::class, 'index'])->name('admin.niveles.index')->middleware('auth');
Route::POST('/admin/niveles/create', [App\Http\Controllers\NivelController::class, 'store'])->name('admin.niveles.store')->middleware('auth');
Route::put('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])->name('admin.niveles.update')->middleware('auth');
Route::delete('/admin/niveles/{gestion}', [\App\Http\Controllers\NivelController::class, 'destroy']);

//Rutas para los Grados
Route::get('/admin/grados', [App\Http\Controllers\GradoController::class, 'index'])->name('admin.grados.index')->middleware('auth');
Route::POST('/admin/grados/create', [App\Http\Controllers\GradoController::class, 'store'])->name('admin.grados.store')->middleware('auth');
Route::put('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'update'])->name('admin.grados.update')->middleware('auth');
Route::delete('/admin/grados/{gestion}', [\App\Http\Controllers\GradoController::class, 'destroy']);

//Rutas para los Paralelos
Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])->name('admin.paralelos.index')->middleware('auth');
Route::POST('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.store')->middleware('auth');
Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])->name('admin.paralelos.update')->middleware('auth');
Route::delete('/admin/paralelos/{gestion}', [\App\Http\Controllers\ParaleloController::class, 'destroy']);

//Ruta para los Turnos
Route::get('/admin/turnos', [App\Http\Controllers\TurnoController::class, 'index'])->name('admin.turnos.index')->middleware('auth');
Route::POST('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])->name('admin.turnos.store')->middleware('auth');
Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])->name('admin.turnos.update')->middleware('auth');
Route::delete('/admin/turnos/{gestion}', [\App\Http\Controllers\TurnoController::class, 'destroy']);

//Ruta para los Materias
Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])->name('materias.index')->middleware('auth');
Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])->name('admin.materias.store')->middleware('auth');
Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])->name('materias.update')->middleware('auth');
Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])->name('materias.destroy')->middleware('auth');

//Ruta para los Roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::post('/admin/roles',  [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::get('/admin/roles/{id}/edit',  [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');
Route::put('/admin/roles/{id}',  [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/roles/{id}',  [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');
Route::get('/admin/roles/permisos/{id}', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth');

//Rutas para el Personal
Route::get('/admin/personal/{tipo?}', [App\Http\Controllers\PersonalController::class, 'index'])->name('admin.personal.index')->middleware('auth');
Route::post('/admin/personal', [App\Http\Controllers\PersonalController::class, 'store'])->name('admin.personal.store')->middleware('auth');
Route::get('/admin/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])->name('admin.personal.edit')->middleware('auth');
Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])->name('admin.personal.update')->middleware('auth');
Route::delete('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'destroy'])->name('admin.personal.destroy')->middleware('auth');

//Rutas para las Formaciones
Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])->name('admin.formacion.index')->middleware('auth');
Route::post('/admin/formaciones', [App\Http\Controllers\FormacionController::class, 'store'])->name('admin.formacion.store')->middleware('auth');
Route::put('/admin/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'update'])->name('admin.formacion.update')->middleware('auth');
Route::delete('/admin/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])->name('admin.formacion.destroy')->middleware('auth');
Route::get('/admin/formaciones/{id}/download', [App\Http\Controllers\FormacionController::class, 'download'])->name('admin.formacion.download')->middleware('auth');

//Rutas para estudiantes
Route::get('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'index'])->name('admin.estudiantes.index')->middleware('auth');
Route::post('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'store'])->name('admin.estudiantes.store')->middleware('auth');
Route::get('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'show'])->name('admin.estudiantes.show')->middleware('auth');
Route::get('/admin/estudiantes/{estudiante}/edit', [App\Http\Controllers\EstudianteController::class, 'edit'])->name('admin.estudiantes.edit')->middleware('auth');
Route::put('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'update'])->name('admin.estudiantes.update')->middleware('auth');
Route::delete('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'destroy'])->name('admin.estudiantes.destroy')->middleware('auth');

// Rutas para PPFF
Route::get('/admin/ppffs', [App\Http\Controllers\PpffController::class, 'index'])->name('admin.ppff.index')->middleware('auth');
Route::post('/admin/ppff', [App\Http\Controllers\PpffController::class, 'store'])->name('admin.ppff.store')->middleware('auth');
Route::get('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'show'])->name('admin.ppff.show')->middleware('auth');
Route::put('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'update'])->name('admin.ppff.update')->middleware('auth');
Route::delete('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'destroy'])->name('admin.ppff.destroy')->middleware('auth');
Route::get('/admin/ppff/search', [App\Http\Controllers\PpffController::class, 'search'])->name('admin.ppff.search')->middleware('auth');

// Rutas adicionales para PPFF (compatibilidad con estudiantes)
Route::get('/admin/estudiantes/ppff/search', [App\Http\Controllers\EstudianteController::class, 'searchPpff'])->name('admin.estudiantes.ppff.search')->middleware('auth');
Route::get('/admin/estudiantes/ppff/{id}/details', [App\Http\Controllers\EstudianteController::class, 'getPpffDetails'])->name('admin.estudiantes.ppff.details')->middleware('auth');

// Rutas para Matriculaciones
Route::get('/admin/matriculaciones', [App\Http\Controllers\MatriculacionController::class, 'index'])->name('admin.matriculaciones.index')->middleware('auth');
Route::post('/admin/matriculaciones', [App\Http\Controllers\MatriculacionController::class, 'store'])->name('admin.matriculaciones.store')->middleware('auth');
Route::get('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'show'])->name('admin.matriculaciones.show')->middleware('auth');
Route::put('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'update'])->name('admin.matriculaciones.update')->middleware('auth');
Route::delete('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'destroy'])->name('admin.matriculaciones.destroy')->middleware('auth');

// Rutas AJAX para matriculaciones
Route::post('/admin/matriculaciones/grados-by-nivel', [App\Http\Controllers\MatriculacionController::class, 'getGradosByNivel'])->name('admin.matriculaciones.grados-by-nivel')->middleware('auth');
Route::post('/admin/matriculaciones/paralelos-by-grado', [App\Http\Controllers\MatriculacionController::class, 'getParalelosByGrado'])->name('admin.matriculaciones.paralelos-by-grado')->middleware('auth');
Route::post('/admin/matriculaciones/estudiantes-disponibles', [App\Http\Controllers\MatriculacionController::class, 'getEstudiantesDisponibles'])->name('admin.matriculaciones.estudiantes-disponibles')->middleware('auth');
Route::get('/admin/matriculaciones/{matriculacion}/pdf', [App\Http\Controllers\MatriculacionController::class, 'generarPDF'])->name('admin.matriculaciones.pdf')->middleware('auth');

// ========== RUTA DE DIAGNÓSTICO MEJORADA - AGREGAR A WEB.PHP ==========
Route::get('/debug-logo-detallado', function() {
    $configuracion = \App\Models\Configuracion::first();
    
    $info = [
        'configuracion_existe' => $configuracion ? 'SÍ' : 'NO',
        'logotipo_campo' => $configuracion->logotipo ?? 'VACÍO',
        'logotipo_info' => $configuracion ? $configuracion->getLogotipoInfo() : 'N/A',
    ];
    
    // Verificar todos los directorios posibles
    $directorios = [
        'public/uploads/logos/' => public_path('uploads/logos/'),
        'public/uploads/' => public_path('uploads/'),
        'storage/app/public/logos/' => storage_path('app/public/logos/'),
        'storage/app/public/' => storage_path('app/public/'),
        'public/' => public_path(),
    ];

    foreach ($directorios as $nombre => $ruta) {
        $info['directorios'][$nombre] = [
            'existe' => is_dir($ruta),
            'ruta_completa' => $ruta,
            'archivos' => []
        ];
        
        if (is_dir($ruta)) {
            $archivos = array_diff(scandir($ruta), ['.', '..']);
            $info['directorios'][$nombre]['archivos'] = array_values($archivos);
            $info['directorios'][$nombre]['permisos'] = [
                'legible' => is_readable($ruta),
                'escribible' => is_writable($ruta)
            ];
        }
    }

    // Si existe configuración, buscar el archivo específico
    if ($configuracion && $configuracion->logotipo) {
        $nombreArchivo = $configuracion->logotipo;
        $info['busqueda_archivo'] = [];
        
        foreach ($directorios as $nombre => $ruta) {
            $rutaCompleta = $ruta . $nombreArchivo;
            $info['busqueda_archivo'][$nombre] = [
                'ruta_completa' => $rutaCompleta,
                'existe' => file_exists($rutaCompleta),
                'tamaño' => file_exists($rutaCompleta) ? filesize($rutaCompleta) : 0,
                'es_imagen' => file_exists($rutaCompleta) ? @getimagesize($rutaCompleta) !== false : false
            ];
        }
    }

    // Información del sistema
    $info['sistema'] = [
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'storage_link_exists' => is_link(public_path('storage')),
        'app_env' => config('app.env'),
        'app_url' => config('app.url')
    ];

    return response()->json($info, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->middleware('auth')->name('debug.logo.detallado');

// ========== RUTAS PARA ASIGNACIONES (CORREGIDAS) ==========
Route::get('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'index'])->name('admin.asignaciones.index')->middleware('auth');
Route::post('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('auth');
Route::get('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'show'])->name('admin.asignaciones.show')->middleware('auth');
Route::get('/admin/asignaciones/{asignacion}/edit', [App\Http\Controllers\AsignacionController::class, 'edit'])->name('admin.asignaciones.edit')->middleware('auth');
Route::put('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'update'])->name('admin.asignaciones.update')->middleware('auth');
Route::delete('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy')->middleware('auth');

// Rutas AJAX para asignaciones
Route::post('/admin/asignaciones/grados-by-nivel', [App\Http\Controllers\AsignacionController::class, 'getGradosByNivel'])->name('admin.asignaciones.grados-by-nivel')->middleware('auth');
Route::post('/admin/asignaciones/paralelos-by-grado', [App\Http\Controllers\AsignacionController::class, 'getParalelosByGrado'])->name('admin.asignaciones.paralelos-by-grado')->middleware('auth');
Route::post('/admin/asignaciones/docentes-disponibles', [App\Http\Controllers\AsignacionController::class, 'getDocentesDisponibles'])->name('admin.asignaciones.docentes-disponibles')->middleware('auth');
Route::post('/admin/asignaciones/verificar-conflicto', [App\Http\Controllers\AsignacionController::class, 'verificarConflicto'])->name('admin.asignaciones.verificar-conflicto')->middleware('auth');
Route::post('/admin/asignaciones/docente-info', [App\Http\Controllers\AsignacionController::class, 'getDocenteInfo'])->name('admin.asignaciones.docente-info')->middleware('auth');
Route::post('/admin/asignaciones/validar-aula', [App\Http\Controllers\AsignacionController::class, 'validarDisponibilidadAula'])->name('admin.asignaciones.validar-aula')->middleware('auth');

// Rutas de Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');