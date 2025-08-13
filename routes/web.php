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

//rutas para la configuracion del sistema
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])
        ->name('admin.configuracion.index')
        ->middleware('can:admin.configuracion.index');
    Route::POST('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])
        ->name('admin.configuracion.store')
        ->middleware('can:admin.configuracion.create');
});

//Rutas para las gestiones del sistema
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/gestiones', [App\Http\Controllers\GestionController::class, 'index'])
        ->name('admin.gestiones.index')
        ->middleware('can:admin.gestiones.index');
    Route::get('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'create'])
        ->name('admin.gestiones.create')
        ->middleware('can:admin.gestiones.create');
    Route::POST('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'store'])
        ->name('admin.gestiones.store')
        ->middleware('can:admin.gestiones.create');
    Route::get('/admin/gestiones/{id}/edit', [App\Http\Controllers\GestionController::class, 'edit'])
        ->name('admin.gestiones.edit')
        ->middleware('can:admin.gestiones.edit');
    Route::put('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'update'])
        ->name('admin.gestiones.update')
        ->middleware('can:admin.gestiones.edit');
    Route::delete('/admin/gestiones/{gestion}', [\App\Http\Controllers\GestionController::class, 'destroy'])
        ->middleware('can:admin.gestiones.delete');
});

//Rutas para los Periodos
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/periodos', [App\Http\Controllers\PeriodoController::class, 'index'])
        ->name('admin.periodos.index')
        ->middleware('can:admin.periodos.index');
    Route::POST('/admin/periodos/create', [App\Http\Controllers\PeriodoController::class, 'store'])
        ->name('admin.periodos.store')
        ->middleware('can:admin.periodos.create');
    Route::put('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'update'])
        ->name('admin.periodos.update')
        ->middleware('can:admin.periodos.edit');
    Route::delete('/admin/periodos/{gestion}', [\App\Http\Controllers\PeriodoController::class, 'destroy'])
        ->middleware('can:admin.periodos.delete');
});

//Rutas para los Niveles
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/niveles', [App\Http\Controllers\NivelController::class, 'index'])
        ->name('admin.niveles.index')
        ->middleware('can:admin.niveles.index');
    Route::POST('/admin/niveles/create', [App\Http\Controllers\NivelController::class, 'store'])
        ->name('admin.niveles.store')
        ->middleware('can:admin.niveles.create');
    Route::put('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])
        ->name('admin.niveles.update')
        ->middleware('can:admin.niveles.edit');
    Route::delete('/admin/niveles/{gestion}', [\App\Http\Controllers\NivelController::class, 'destroy'])
        ->middleware('can:admin.niveles.delete');
});

//Rutas para los Grados
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/grados', [App\Http\Controllers\GradoController::class, 'index'])
        ->name('admin.grados.index')
        ->middleware('can:admin.grados.index');
    Route::POST('/admin/grados/create', [App\Http\Controllers\GradoController::class, 'store'])
        ->name('admin.grados.store')
        ->middleware('can:admin.grados.create');
    Route::put('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'update'])
        ->name('admin.grados.update')
        ->middleware('can:admin.grados.edit');
    Route::delete('/admin/grados/{gestion}', [\App\Http\Controllers\GradoController::class, 'destroy'])
        ->middleware('can:admin.grados.delete');
});

//Rutas para los Paralelos
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])
        ->name('admin.paralelos.index')
        ->middleware('can:admin.paralelos.index');
    Route::POST('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])
        ->name('admin.paralelos.store')
        ->middleware('can:admin.paralelos.create');
    Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])
        ->name('admin.paralelos.update')
        ->middleware('can:admin.paralelos.edit');
    Route::delete('/admin/paralelos/{gestion}', [\App\Http\Controllers\ParaleloController::class, 'destroy'])
        ->middleware('can:admin.paralelos.delete');
});

//Ruta para los Turnos
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/turnos', [App\Http\Controllers\TurnoController::class, 'index'])
        ->name('admin.turnos.index')
        ->middleware('can:admin.turnos.index');
    Route::POST('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])
        ->name('admin.turnos.store')
        ->middleware('can:admin.turnos.create');
    Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])
        ->name('admin.turnos.update')
        ->middleware('can:admin.turnos.edit');
    Route::delete('/admin/turnos/{gestion}', [\App\Http\Controllers\TurnoController::class, 'destroy'])
        ->middleware('can:admin.turnos.delete');
});

//Ruta para los Materias
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])
        ->name('materias.index')
        ->middleware('can:admin.materias.index');
    Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])
        ->name('admin.materias.store')
        ->middleware('can:admin.materias.create');
    Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])
        ->name('materias.update')
        ->middleware('can:admin.materias.edit');
    Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])
        ->name('materias.destroy')
        ->middleware('can:admin.materias.delete');
});

//Ruta para los Roles
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])
        ->name('admin.roles.index')
        ->middleware('can:admin.roles.index');
    Route::post('/admin/roles',  [App\Http\Controllers\RoleController::class, 'store'])
        ->name('admin.roles.store')
        ->middleware('can:admin.roles.create');
    Route::get('/admin/roles/{id}/edit',  [App\Http\Controllers\RoleController::class, 'edit'])
        ->name('admin.roles.edit')
        ->middleware('can:admin.roles.edit');
    Route::put('/admin/roles/{id}',  [App\Http\Controllers\RoleController::class, 'update'])
        ->name('admin.roles.update')
        ->middleware('can:admin.roles.edit');
    Route::delete('/admin/roles/{id}',  [App\Http\Controllers\RoleController::class, 'destroy'])
        ->name('admin.roles.destroy')
        ->middleware('can:admin.roles.delete');
    Route::get('/admin/roles/permisos/{id}', [App\Http\Controllers\RoleController::class, 'permisos'])
        ->name('admin.roles.permisos')
        ->middleware('can:admin.roles.permisos');
    Route::get('admin/roles/{role}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])
        ->name('admin.roles.permisos')
        ->middleware('can:admin.roles.permisos');
    Route::post('admin/roles/{role}/asignar-permisos', [App\Http\Controllers\RoleController::class, 'asignarPermisos'])
        ->name('admin.roles.asignar-permisos')
        ->middleware('can:admin.roles.permisos');
});

//Rutas para el Personal
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/personal/{tipo?}', [App\Http\Controllers\PersonalController::class, 'index'])
        ->name('admin.personal.index')
        ->middleware('can:admin.personal.index');
    Route::post('/admin/personal', [App\Http\Controllers\PersonalController::class, 'store'])
        ->name('admin.personal.store')
        ->middleware('can:admin.personal.create');
    Route::get('/admin/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])
        ->name('admin.personal.edit')
        ->middleware('can:admin.personal.edit');
    Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])
        ->name('admin.personal.update')
        ->middleware('can:admin.personal.edit');
    Route::delete('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'destroy'])
        ->name('admin.personal.destroy')
        ->middleware('can:admin.personal.delete');
});

//Rutas para las Formaciones
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])
        ->name('admin.formacion.index')
        ->middleware('can:admin.formaciones.index');
    Route::post('/admin/formaciones', [App\Http\Controllers\FormacionController::class, 'store'])
        ->name('admin.formacion.store')
        ->middleware('can:admin.formaciones.create');
    Route::put('/admin/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'update'])
        ->name('admin.formacion.update')
        ->middleware('can:admin.formaciones.edit');
    Route::delete('/admin/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])
        ->name('admin.formacion.destroy')
        ->middleware('can:admin.formaciones.delete');
    Route::get('/admin/formaciones/{id}/download', [App\Http\Controllers\FormacionController::class, 'download'])
        ->name('admin.formacion.download')
        ->middleware('can:admin.formaciones.index');
});

//Rutas para estudiantes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'index'])
        ->name('admin.estudiantes.index')
        ->middleware('can:admin.estudiantes.index');
    Route::post('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'store'])
        ->name('admin.estudiantes.store')
        ->middleware('can:admin.estudiantes.create');
    Route::get('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'show'])
        ->name('admin.estudiantes.show')
        ->middleware('can:admin.estudiantes.index');
    Route::get('/admin/estudiantes/{estudiante}/edit', [App\Http\Controllers\EstudianteController::class, 'edit'])
        ->name('admin.estudiantes.edit')
        ->middleware('can:admin.estudiantes.edit');
    Route::put('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'update'])
        ->name('admin.estudiantes.update')
        ->middleware('can:admin.estudiantes.edit');
    Route::delete('/admin/estudiantes/{estudiante}', [App\Http\Controllers\EstudianteController::class, 'destroy'])
        ->name('admin.estudiantes.destroy')
        ->middleware('can:admin.estudiantes.delete');
});

// Rutas para PPFF
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/ppffs', [App\Http\Controllers\PpffController::class, 'index'])
        ->name('admin.ppff.index')
        ->middleware('can:admin.ppffs.index');
    Route::post('/admin/ppff', [App\Http\Controllers\PpffController::class, 'store'])
        ->name('admin.ppff.store')
        ->middleware('can:admin.ppffs.create');
    Route::get('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'show'])
        ->name('admin.ppff.show')
        ->middleware('can:admin.ppffs.index');
    Route::put('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'update'])
        ->name('admin.ppff.update')
        ->middleware('can:admin.ppffs.edit');
    Route::delete('/admin/ppff/{ppff}', [App\Http\Controllers\PpffController::class, 'destroy'])
        ->name('admin.ppff.destroy')
        ->middleware('can:admin.ppffs.delete');
    Route::get('/admin/ppff/search', [App\Http\Controllers\PpffController::class, 'search'])
        ->name('admin.ppff.search')
        ->middleware('can:admin.ppffs.index');
});

// Rutas adicionales para PPFF (compatibilidad con estudiantes)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/estudiantes/ppff/search', [App\Http\Controllers\EstudianteController::class, 'searchPpff'])
        ->name('admin.estudiantes.ppff.search')
        ->middleware('can:admin.estudiantes.index');
    Route::get('/admin/estudiantes/ppff/{id}/details', [App\Http\Controllers\EstudianteController::class, 'getPpffDetails'])
        ->name('admin.estudiantes.ppff.details')
        ->middleware('can:admin.estudiantes.index');
});

// Rutas para Matriculaciones
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/matriculaciones', [App\Http\Controllers\MatriculacionController::class, 'index'])
        ->name('admin.matriculaciones.index')
        ->middleware('can:admin.matriculaciones.index');
    Route::post('/admin/matriculaciones', [App\Http\Controllers\MatriculacionController::class, 'store'])
        ->name('admin.matriculaciones.store')
        ->middleware('can:admin.matriculaciones.create');
    Route::get('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'show'])
        ->name('admin.matriculaciones.show')
        ->middleware('can:admin.matriculaciones.index');
    Route::put('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'update'])
        ->name('admin.matriculaciones.update')
        ->middleware('can:admin.matriculaciones.edit');
    Route::delete('/admin/matriculaciones/{matriculacion}', [App\Http\Controllers\MatriculacionController::class, 'destroy'])
        ->name('admin.matriculaciones.destroy')
        ->middleware('can:admin.matriculaciones.delete');
});

// Rutas AJAX para matriculaciones
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/matriculaciones/grados-by-nivel', [App\Http\Controllers\MatriculacionController::class, 'getGradosByNivel'])
        ->name('admin.matriculaciones.grados-by-nivel')
        ->middleware('can:admin.matriculaciones.index');
    Route::post('/admin/matriculaciones/paralelos-by-grado', [App\Http\Controllers\MatriculacionController::class, 'getParalelosByGrado'])
        ->name('admin.matriculaciones.paralelos-by-grado')
        ->middleware('can:admin.matriculaciones.index');
    Route::post('/admin/matriculaciones/estudiantes-disponibles', [App\Http\Controllers\MatriculacionController::class, 'getEstudiantesDisponibles'])
        ->name('admin.matriculaciones.estudiantes-disponibles')
        ->middleware('can:admin.matriculaciones.index');
    Route::get('/admin/matriculaciones/{matriculacion}/pdf', [App\Http\Controllers\MatriculacionController::class, 'generarPDF'])
        ->name('admin.matriculaciones.pdf')
        ->middleware('can:admin.matriculaciones.index');
});

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
Route::middleware(['auth'])->group(function () {
Route::get('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'index'])->name('admin.asignaciones.index')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('can:admin.asignaciones.create');
Route::get('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'show'])->name('admin.asignaciones.show')->middleware('can:admin.asignaciones.index');
Route::get('/admin/asignaciones/{asignacion}/edit', [App\Http\Controllers\AsignacionController::class, 'edit'])->name('admin.asignaciones.edit')->middleware('can:admin.asignaciones.edit');
Route::put('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'update'])->name('admin.asignaciones.update')->middleware('can:admin.asignaciones.edit');
Route::delete('/admin/asignaciones/{asignacion}', [App\Http\Controllers\AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy')->middleware('can:admin.asignaciones.delete');
});

// Rutas AJAX para asignaciones
Route::middleware(['auth'])->group(function () {
Route::post('/admin/asignaciones/grados-by-nivel', [App\Http\Controllers\AsignacionController::class, 'getGradosByNivel'])->name('admin.asignaciones.grados-by-nivel')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones/paralelos-by-grado', [App\Http\Controllers\AsignacionController::class, 'getParalelosByGrado'])->name('admin.asignaciones.paralelos-by-grado')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones/docentes-disponibles', [App\Http\Controllers\AsignacionController::class, 'getDocentesDisponibles'])->name('admin.asignaciones.docentes-disponibles')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones/verificar-conflicto', [App\Http\Controllers\AsignacionController::class, 'verificarConflicto'])->name('admin.asignaciones.verificar-conflicto')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones/docente-info', [App\Http\Controllers\AsignacionController::class, 'getDocenteInfo'])->name('admin.asignaciones.docente-info')->middleware('can:admin.asignaciones.index');
Route::post('/admin/asignaciones/validar-aula', [App\Http\Controllers\AsignacionController::class, 'validarDisponibilidadAula'])->name('admin.asignaciones.validar-aula')->middleware('can:admin.asignaciones.index');
});

//Rutas para pagos (AGREGAR ESTAS AL FINAL DE TU WEB.PHP)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/pagos', [App\Http\Controllers\PagoController::class, 'index'])->name('admin.pagos.index')->middleware('can:admin.pagos.index');
    Route::get('/admin/pagos/estudiante/{id}', [App\Http\Controllers\PagoController::class, 'ver_pagos'])->name('admin.pagos.ver_pagos')->middleware('can:admin.pagos.ver_pagos');
    Route::post('/admin/pagos/create', [App\Http\Controllers\PagoController::class, 'store'])->name('admin.pagos.store')->middleware('can:admin.pagos.store');
    Route::put('/admin/pagos/{id}', [App\Http\Controllers\PagoController::class, 'update'])->name('admin.pagos.update')->middleware('can:admin.pagos.store');
    Route::get('/admin/pagos/{id}/comprobante', [App\Http\Controllers\PagoController::class, 'comprobante'])->name('admin.pagos.comprobante')->middleware('can:admin.pagos.comprobante');
    Route::delete('/admin/pagos/{id}', [App\Http\Controllers\PagoController::class, 'destroy'])->name('admin.pagos.destroy')->middleware('can:admin.pagos.destroy');
    Route::get('/admin/pagos/reportes', [App\Http\Controllers\PagoController::class, 'reportes'])->name('admin.pagos.reportes')->middleware('can:admin.pagos.reportes');
    
    // Rutas AJAX
    Route::post('/admin/pagos/matriculaciones-by-estudiante', [App\Http\Controllers\PagoController::class, 'getMatriculacionesByEstudiante'])->name('admin.pagos.matriculaciones-by-estudiante')->middleware('can:admin.pagos.index');
});

// Rutas para Asistencias (CORREGIDAS)
Route::middleware(['auth'])->group(function () {
    // Ruta principal - cambié de POST a GET
    Route::get('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index')->middleware('can:admin.asistencias.index');
    // Formulario para crear asistencia
    Route::get('/admin/asistencias/create/asignacion/{id}', action: [App\Http\Controllers\AsistenciaController::class, 'create'])->name('admin.asistencias.create')->middleware('can:admin.asistencias.create');
    // Guardar asistencias
    Route::post('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'store'])->name('admin.asistencias.store')->middleware('can:admin.asistencias.create');
    // Ver detalles de asistencias de una asignación
    Route::get('/admin/asistencias/{asignacion}/show', [App\Http\Controllers\AsistenciaController::class, 'show'])->name('admin.asistencias.show')->middleware('can:admin.asistencias.index');
    // Editar asistencia individual
    Route::get('/admin/asistencias/{asistencia}/edit', [App\Http\Controllers\AsistenciaController::class, 'edit'])->name('admin.asistencias.edit')->middleware('can:admin.asistencias.edit');
    // Actualizar asistencia individual
    Route::put('/admin/asistencias/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update')->middleware('can:admin.asistencias.edit');
    // Eliminar asistencia individual
    Route::delete('/admin/asistencias/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'destroy'])->name('admin.asistencias.destroy')->middleware('can:admin.asistencias.delete');
    // Generar reporte de asistencias
    Route::get('/admin/asistencias/reporte', [App\Http\Controllers\AsistenciaController::class, 'reporte'])->name('admin.asistencias.reporte')->middleware('can:admin.asistencias.index');
});

//Rutas para Calificaciones
Route::get('/admin/calificaciones', [App\Http\Controllers\CalificacionController::class, 'index'])->name('admin.calificaciones.index')->middleware('can:admin.calificaciones.index');
Route::get('/admin/calificaciones/create/asignacion/{id}', action: [App\Http\Controllers\CalificacionController::class, 'create'])->name('admin.calificaciones.create')->middleware('can:admin.calificaciones.create');
Route::post('/admin/calificaciones', [App\Http\Controllers\CalificacionController::class, 'store'])->name('admin.calificaciones.store')->middleware('can:admin.calificaciones.create');
Route::put('/admin/calificaciones/{id}', [App\Http\Controllers\CalificacionController::class, 'update'])->name('admin.calificaciones.update')->middleware('can:admin.calificaciones.update');
Route::get('/admin/calificaciones/detalle/asignacion/{id_asignacion}/estudiante/{id_estudiante}', [App\Http\Controllers\CalificacionController::class, 'show_estudiante'])->name('admin.calificaciones.show_estudiante')->middleware('can:admin.calificaciones.show_estudiante');
Route::get('/admin/calificaciones/asignacion/{id}', [App\Http\Controllers\CalificacionController::class, 'show_admin'])->name('admin.calificaciones.show_admin')->middleware('can:admin.calificaciones.show_admin');
Route::delete('/admin/calificaciones/asignacion/{id}', [App\Http\Controllers\CalificacionController::class, 'destroy'])->name('admin.calificaciones.destroy')->middleware('can:admin.calificaciones.destroy');



// Rutas de Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');