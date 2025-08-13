@extends('adminlte::page')

@section('content_header')
    <h1>Detalles de Asistencias</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.asistencias.index') }}">Asistencias</a></li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@stop

@section('content')
<div class="row">
    <!-- Información de la Asignación -->
    <div class="col-md-12 mb-3">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información de la Asignación</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Docente:</strong> {{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno . ' ' . $asignacion->docente->materno : 'Sin docente' }}</p>
                        <p><strong>Gestión:</strong> <span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span></p>
                        <p><strong>Nivel:</strong> {{ $asignacion->nivel->nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Grado y Paralelo:</strong> {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
                        <p><strong>Materia:</strong> <span class="badge badge-info">{{ $asignacion->materia->nombre }}</span></p>
                        <p><strong>Turno:</strong> <span class="badge badge-warning">{{ $asignacion->turno->nombre }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Búsqueda</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.asistencias.show', $asignacion->id) }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" 
                                       value="{{ $fechaInicio }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" 
                                       value="{{ $fechaFin }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('admin.asistencias.show', $asignacion->id) }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-undo"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Resumen Estadístico -->
    @if($asistenciasPorEstudiante->count() > 0)
        <div class="col-md-12 mb-3">
            <div class="row">
                @php
                    $totalRegistros = $asistenciasPorEstudiante->flatten()->count();
                    $presentes = $asistenciasPorEstudiante->flatten()->where('estado', 'presente')->count();
                    $ausentes = $asistenciasPorEstudiante->flatten()->where('estado', 'ausente')->count();
                    $tardes = $asistenciasPorEstudiante->flatten()->where('estado', 'tarde')->count();
                    $justificados = $asistenciasPorEstudiante->flatten()->where('estado', 'justificado')->count();
                @endphp
                
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $presentes }}</h3>
                            <p>Presentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $ausentes }}</h3>
                            <p>Ausentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $tardes }}</h3>
                            <p>Tardanzas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $justificados }}</h3>
                            <p>Justificadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabla de Asistencias por Estudiante -->
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>Registro de Asistencias por Estudiante
                </h3>
                <div class="card-tools">
                    <span class="badge badge-primary">
                        Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                    </span>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($asistenciasPorEstudiante->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>CI</th>
                                    <th>Total Días</th>
                                    <th>Presentes</th>
                                    <th>Ausentes</th>
                                    <th>Tardanzas</th>
                                    <th>Justificadas</th>
                                    <th>% Asistencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistenciasPorEstudiante as $estudianteId => $asistenciasEstudiante)
                                    @php
                                        $estudiante = $estudiantes[$estudianteId] ?? $asistenciasEstudiante->first()->estudiante;
                                        $total = $asistenciasEstudiante->count();
                                        $presente = $asistenciasEstudiante->where('estado', 'presente')->count();
                                        $ausente = $asistenciasEstudiante->where('estado', 'ausente')->count();
                                        $tarde = $asistenciasEstudiante->where('estado', 'tarde')->count();
                                        $justificado = $asistenciasEstudiante->where('estado', 'justificado')->count();
                                        $porcentaje = $total > 0 ? round(($presente + $tarde) / $total * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}</strong>
                                        </td>
                                        <td>{{ $estudiante->ci }}</td>
                                        <td><span class="badge badge-secondary">{{ $total }}</span></td>
                                        <td><span class="badge badge-success">{{ $presente }}</span></td>
                                        <td><span class="badge badge-danger">{{ $ausente }}</span></td>
                                        <td><span class="badge badge-warning">{{ $tarde }}</span></td>
                                        <td><span class="badge badge-info">{{ $justificado }}</span></td>
                                        <td>
                                            <span class="badge badge-{{ $porcentaje >= 80 ? 'success' : ($porcentaje >= 60 ? 'warning' : 'danger') }}">
                                                {{ $porcentaje }}%
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#modalDetalle{{ $estudianteId }}"
                                                    title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Botones de acción -->
                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('admin.asistencias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        
                        <div>
                            <a href="{{ route('admin.asistencias.create', $asignacion->id) }}" class="btn btn-success">
                                <i class="fas fa-calendar-plus"></i> Nueva Asistencia
                            </a>
                            <button type="button" 
                                    class="btn btn-warning ml-2" 
                                    data-toggle="modal" 
                                    data-target="#modalReporteGeneral">
                                <i class="fas fa-chart-bar"></i> Generar Reporte
                            </button>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <h4><i class="fas fa-info-circle"></i> Sin registros de asistencia</h4>
                        <p>No hay registros de asistencia para el período seleccionado.</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.asistencias.create', $asignacion->id) }}" class="btn btn-success">
                                <i class="fas fa-calendar-plus"></i> Registrar Primera Asistencia
                            </a>
                            <a href="{{ route('admin.asistencias.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modales de Detalle por Estudiante -->
@if($asistenciasPorEstudiante->count() > 0)
    @foreach($asistenciasPorEstudiante as $estudianteId => $asistenciasEstudiante)
        @php
            $estudiante = $estudiantes[$estudianteId] ?? $asistenciasEstudiante->first()->estudiante;
        @endphp
        <div class="modal fade" id="modalDetalle{{ $estudianteId }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-graduate mr-2"></i>
                            Detalle de Asistencias - {{ $estudiante->nombre . ' ' . $estudiante->paterno }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Día</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asistenciasEstudiante->sortBy('fecha') as $asistencia)
                                        <tr>
                                            <td>{{ $asistencia->fecha_formateada }}</td>
                                            <td>{{ $asistencia->dia_semana }}</td>
                                            <td>
                                                @switch($asistencia->estado)
                                                    @case('presente')
                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> PRESENTE</span>
                                                        @break
                                                    @case('ausente')
                                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> AUSENTE</span>
                                                        @break
                                                    @case('tarde')
                                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> TARDE</span>
                                                        @break
                                                    @case('justificado')
                                                        <span class="badge badge-info"><i class="fas fa-file-medical"></i> JUSTIFICADO</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($asistencia->observaciones)
                                                    <small>{{ $asistencia->observaciones }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @can('admin.asistencias.edit')
                                                    <a href="{{ route('admin.asistencias.edit', $asistencia->id) }}" 
                                                       class="btn btn-xs btn-warning" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<!-- Modal para Reporte General -->
<div class="modal fade" id="modalReporteGeneral" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Generar Reporte de Asistencias</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.asistencias.reporte') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Se generará un reporte detallado con estadísticas de asistencia para todos los estudiantes.
                    </div>

                    <div class="form-group">
                        <label>Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" class="form-control" 
                               value="{{ $fechaInicio }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Fecha de Fin *</label>
                        <input type="date" name="fecha_fin" class="form-control" 
                               value="{{ $fechaFin }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </button>
                </div>
            </form>
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
        .card-outline.card-info {
            border-top-color: #17a2b8;
        }
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .badge {
            font-size: 0.75em;
        }
        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
            line-height: 1;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Validación de fechas
        $('input[name="fecha_inicio"], input[name="fecha_fin"]').on('change', function() {
            var fechaInicio = $('input[name="fecha_inicio"]').val();
            var fechaFin = $('input[name="fecha_fin"]').val();
            
            if (fechaInicio && fechaFin) {
                if (new Date(fechaInicio) > new Date(fechaFin)) {
                    alert('La fecha de inicio no puede ser mayor a la fecha fin.');
                    $(this).val('');
                }
            }
        });
    });
</script>
@stop