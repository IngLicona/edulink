@extends('adminlte::page')

@section('title', 'Calificaciones')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1>Calificaciones</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3>Detalles de la Asignación</h3>
                </div>
                <div class="col text-right">
                    <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Nueva Calificación
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Docente:</strong> {{ $asignacion->docente->nombre_completo }}
                </div>
                <div class="col-md-3">
                    <strong>Gestión:</strong> {{ $asignacion->gestion->nombre }}
                </div>
                <div class="col-md-3">
                    <strong>Materia:</strong> {{ $asignacion->materia->nombre }}
                </div>
                <div class="col-md-3">
                    <strong>Turno:</strong> {{ $asignacion->turno->nombre }}
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Nivel:</strong> {{ $asignacion->nivel->nombre }}
                </div>
                <div class="col-md-3">
                    <strong>Grado:</strong> {{ $asignacion->grado->nombre }}
                </div>
                <div class="col-md-3">
                    <strong>Paralelo:</strong> {{ $asignacion->paralelo->nombre }}
                </div>
            </div>
        </div>
    </div>

    @if($estudiantes->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h3>Calificaciones Registradas</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                @foreach($periodos as $periodo)
                                    <th colspan="2" class="text-center">{{ $periodo->nombre }}</th>
                                @endforeach
                                <th class="text-center">Promedio Final</th>
                                <th>Acciones</th>
                            </tr>
                            <tr>
                                <th></th>
                                @foreach($periodos as $periodo)
                                    <th class="text-center">Calificaciones</th>
                                    <th class="text-center">Promedio</th>
                                @endforeach
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiantes as $estudiante)
                                <tr>
                                    <td>
                                        {{ $estudiante->paterno }} {{ $estudiante->materno }} {{ $estudiante->nombre }}
                                    </td>
                                    @php
                                        $promedioFinal = 0;
                                        $periodosConNotas = 0;
                                    @endphp
                                    @foreach($periodos as $periodo)
                                        @php
                                            $notasPeriodo = $calificaciones
                                                ->where('periodo_id', $periodo->id)
                                                ->flatMap(function($cal) use ($estudiante) {
                                                    return $cal->detalleCalificaciones
                                                        ->where('estudiante_id', $estudiante->id)
                                                        ->pluck('nota');
                                                });
                                            
                                            $promedioPeriodo = $notasPeriodo->count() > 0 ? round($notasPeriodo->avg(), 2) : 0;
                                            if($promedioPeriodo > 0) {
                                                $promedioFinal += $promedioPeriodo;
                                                $periodosConNotas++;
                                            }
                                        @endphp
                                        <td class="text-center">
                                            {{ $notasPeriodo->implode(', ') }}
                                        </td>
                                        <td class="text-center font-weight-bold">
                                            {{ $promedioPeriodo > 0 ? $promedioPeriodo : '-' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center font-weight-bold">
                                        {{ $periodosConNotas > 0 ? round($promedioFinal / $periodosConNotas, 2) : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.calificaciones.show_estudiante', ['id_asignacion' => $asignacion->id, 'id_estudiante' => $estudiante->id]) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Registro de Calificaciones</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Periodo</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calificaciones as $calificacion)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($calificacion->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $calificacion->periodo->nombre }}</td>
                                    <td>{{ $calificacion->tipo }}</td>
                                    <td>{{ $calificacion->descripcion }}</td>
                                    <td>
                                        <a href="{{ route('admin.calificaciones.edit', $calificacion->id) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.calificaciones.destroy', $calificacion->id) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Está seguro de eliminar esta calificación?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-4">
            No hay estudiantes matriculados en esta asignación.
        </div>
    @endif

    <div class="text-right mt-4">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalReporte">
            <i class="fas fa-file-pdf"></i> Generar Reporte
        </button>
        <a href="{{ route('admin.calificaciones.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Modal para Reporte -->
    <div class="modal fade" id="modalReporte" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Reporte de Calificaciones</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.calificaciones.reporte', ['asignacion_id' => $asignacion->id]) }}" method="GET" target="_blank">
                    <div class="modal-body">
                        <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">
                        
                        <div class="card card-outline card-info mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Información de la Asignación</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Docente:</strong> {{ $asignacion->docente->nombre_completo }}</p>
                                <p><strong>Curso:</strong> {{ $asignacion->nivel->nombre }} - {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
                                <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                                <p class="mb-0"><strong>Turno:</strong> {{ $asignacion->turno->nombre }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tipo de Reporte</label>
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-primary active" id="porPeriodo">
                                    <input type="radio" name="tipoReporte" value="periodo" checked> Por Período
                                </label>
                                <label class="btn btn-outline-primary" id="general">
                                    <input type="radio" name="tipoReporte" value="general"> General
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="periodoSelector">
                            <label>Período *</label>
                            <select name="periodo_id" class="form-control" required>
                                <option value="">Seleccionar período</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning" id="btnGenerarReporte">
                            <i class="fas fa-chart-bar"></i> Generar Reporte
                        </button>
                    </div>

                    <input type="hidden" name="general" value="0" id="reporteGeneral">
                
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Manejar el cambio entre tipos de reporte
            $('input[name="tipoReporte"]').change(function() {
                if ($(this).val() === 'general') {
                    $('#periodoSelector').hide();
                    $('#periodoSelector select').prop('required', false);
                    $('#reporteGeneral').val('1');
                } else {
                    $('#periodoSelector').show();
                    $('#periodoSelector select').prop('required', true);
                    $('#reporteGeneral').val('0');
                }
            });
        });
    </script>
@stop
