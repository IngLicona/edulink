@extends('adminlte::page')

@section('content_header')
    <h1>Mis Calificaciones</h1>
    <div class="alert alert-info">
        <i class="fas fa-user-graduate mr-2"></i>
        <strong>Estudiante:</strong> {{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}
        <strong class="ml-3">CI:</strong> {{ $estudiante->ci }}
    </div>
@stop

@section('content')
<div class="row">
    <!-- Resumen de Calificaciones -->
    <div class="col-md-12 mb-3">
        @php
            $totalMaterias = $matriculaciones->count();
            $materiasAprobadas = 0;
            $materiasReprobadas = 0;
            $totalCalificaciones = 0;
            $sumaCalificaciones = 0;
            
            foreach($matriculaciones as $matriculacion) {
                if(isset($calificaciones[$matriculacion->asignacion_id]) && $calificaciones[$matriculacion->asignacion_id]->count() > 0) {
                    $totalNotas = 0;
                    $cantidadNotas = 0;
                    
                    foreach($calificaciones[$matriculacion->asignacion_id] as $cal) {
                        $totalNotas += $cal->nota;
                        $cantidadNotas++;
                        $totalCalificaciones++;
                        $sumaCalificaciones += $cal->nota;
                    }
                    
                    if($cantidadNotas > 0) {
                        $promedio = $totalNotas / $cantidadNotas;
                        if($promedio >= 60) {
                            $materiasAprobadas++;
                        } else {
                            $materiasReprobadas++;
                        }
                    }
                }
            }
            
            $promedioGeneral = $totalCalificaciones > 0 ? $sumaCalificaciones / $totalCalificaciones : 0;
        @endphp
        
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalMaterias }}</h3>
                        <p>Materias Matriculadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $materiasAprobadas }}</h3>
                        <p>Materias Aprobadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $materiasReprobadas }}</h3>
                        <p>Materias Reprobadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($promedioGeneral, 2) }}</h3>
                        <p>Promedio General</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-graduation-cap mr-2"></i>Mis Calificaciones por Materia</h3>
                
                <div class="d-flex align-items-center">
                    <label for="filtroMateria" class="mr-2 mb-0">Filtrar por Materia:</label>
                    <select id="filtroMateria" class="form-control form-control-sm" style="width: 200px;">
                        <option value="">Todas las materias</option>
                        @foreach($matriculaciones as $matriculacion)
                            <option value="{{ $matriculacion->asignacion->materia->nombre }}">{{ $matriculacion->asignacion->materia->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @forelse($matriculaciones as $matriculacion)
                <div class="card card-outline card-secondary mb-3 materia-card" data-materia="{{ $matriculacion->asignacion->materia->nombre }}">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-book mr-2"></i>
                            {{ $matriculacion->asignacion->materia->nombre }}
                        </h5>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $matriculacion->asignacion->gestion->nombre }}</span>
                            <span class="badge badge-secondary">{{ $matriculacion->asignacion->nivel->nombre }}</span>
                            <span class="badge badge-primary">{{ $matriculacion->asignacion->grado->nombre }} "{{ $matriculacion->asignacion->paralelo->nombre }}"</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Docente:</strong> {{ $matriculacion->asignacion->docente->nombre }} {{ $matriculacion->asignacion->docente->paterno }}
                            </div>
                            <div class="col-md-6">
                                <strong>Turno:</strong> {{ $matriculacion->asignacion->turno->nombre }}
                            </div>
                        </div>

                        @if(isset($calificaciones[$matriculacion->asignacion_id]) && $calificaciones[$matriculacion->asignacion_id]->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Período</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Nota</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalNotas = 0;
                                        $cantidadNotas = 0;
                                    @endphp
                                    @foreach($calificaciones[$matriculacion->asignacion_id] as $calificacion)
                                    @php
                                        $totalNotas += $calificacion->nota;
                                        $cantidadNotas++;
                                    @endphp
                                    <tr>
                                        <td><span class="badge badge-info">{{ $calificacion->calificacion->periodo->nombre }}</span></td>
                                        <td>{{ $calificacion->calificacion->tipo }}</td>
                                        <td>{{ $calificacion->calificacion->descripcion ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($calificacion->calificacion->fecha)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $calificacion->nota >= 60 ? 'success' : 'danger' }} badge-lg">
                                                {{ number_format($calificacion->nota, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($calificacion->nota >= 60)
                                                <i class="fas fa-check-circle text-success" title="Aprobado"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger" title="Reprobado"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($cantidadNotas > 0)
                                <tfoot>
                                    <tr class="bg-light">
                                        <th colspan="4">Promedio de la Materia</th>
                                        <th class="text-center">
                                            @php
                                                $promedio = $totalNotas / $cantidadNotas;
                                            @endphp
                                            <span class="badge badge-{{ $promedio >= 60 ? 'success' : 'danger' }} badge-xl">
                                                {{ number_format($promedio, 2) }}
                                            </span>
                                        </th>
                                        <th class="text-center">
                                            @if($promedio >= 60)
                                                <i class="fas fa-trophy text-warning" title="Materia Aprobada"></i>
                                            @else
                                                <i class="fas fa-exclamation-triangle text-danger" title="Materia Reprobada"></i>
                                            @endif
                                        </th>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay calificaciones registradas para esta materia.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                        <h4>No hay matriculaciones activas</h4>
                        <p class="text-muted">No tienes materias asignadas en este momento.</p>
                    </div>
                </div>
                @endforelse

                <!-- Estadísticas detalladas -->
                @if($matriculaciones->count() > 0 && $totalCalificaciones > 0)
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Estadísticas Académicas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Total de evaluaciones:</strong> {{ $totalCalificaciones }}</p>
                                <p><strong>Promedio general:</strong> 
                                    <span class="badge badge-{{ $promedioGeneral >= 60 ? 'success' : 'danger' }} badge-lg">
                                        {{ number_format($promedioGeneral, 2) }}
                                    </span>
                                </p>
                                <p><strong>Rendimiento académico:</strong> 
                                    @if($promedioGeneral >= 90)
                                        <span class="badge badge-success">Excelente</span>
                                    @elseif($promedioGeneral >= 80)
                                        <span class="badge badge-info">Muy Bueno</span>
                                    @elseif($promedioGeneral >= 70)
                                        <span class="badge badge-warning">Bueno</span>
                                    @elseif($promedioGeneral >= 60)
                                        <span class="badge badge-secondary">Regular</span>
                                    @else
                                        <span class="badge badge-danger">Necesita Mejorar</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($promedioGeneral >= 80)
                                    <div class="alert alert-success">
                                        <i class="fas fa-trophy mr-2"></i>¡Excelente rendimiento académico!
                                    </div>
                                @elseif($promedioGeneral >= 60)
                                    <div class="alert alert-info">
                                        <i class="fas fa-thumbs-up mr-2"></i>Buen rendimiento. ¡Sigue así!
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Necesitas mejorar tu rendimiento.
                                    </div>
                                @endif
                                
                                <p><strong>Materias aprobadas:</strong> {{ $materiasAprobadas }} de {{ $totalMaterias }}</p>
                                
                                @if($totalMaterias > 0)
                                    @php
                                        $porcentajeAprobacion = ($materiasAprobadas / $totalMaterias) * 100;
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar bg-{{ $porcentajeAprobacion >= 70 ? 'success' : ($porcentajeAprobacion >= 50 ? 'warning' : 'danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $porcentajeAprobacion }}%" 
                                             aria-valuenow="{{ $porcentajeAprobacion }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($porcentajeAprobacion, 1) }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">Porcentaje de aprobación</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .small-box .inner h3 {
            font-size: 2.2rem;
            font-weight: bold;
        }
        .card-outline {
            border-top: 3px solid;
        }
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .card-outline.card-secondary {
            border-top-color: #6c757d;
        }
        .badge-lg {
            font-size: 1em;
            padding: 0.5em 0.75em;
        }
        .badge-xl {
            font-size: 1.1em;
            padding: 0.6em 0.85em;
            font-weight: bold;
        }
        .table td {
            vertical-align: middle;
        }
        .progress {
            height: 25px;
        }
        .progress-bar {
            font-weight: bold;
            line-height: 25px;
        }
    </style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Filtro por materia
        $('#filtroMateria').on('change', function() {
            var materia = this.value.toLowerCase();
            
            $('.materia-card').each(function() {
                var cardMateria = $(this).data('materia').toLowerCase();
                
                if (materia === '' || cardMateria.includes(materia)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Inicializar tooltips
        $('[title]').tooltip();

        // Animación para las tarjetas
        $('.materia-card').each(function(index) {
            $(this).hide().delay(index * 100).fadeIn(500);
        });
    });
</script>
@stop