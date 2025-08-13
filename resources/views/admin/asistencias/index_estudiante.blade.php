@extends('adminlte::page')

@section('content_header')
    <h1>Mi Registro de Asistencias</h1>
    <div class="alert alert-info">
        <i class="fas fa-user-graduate mr-2"></i>
        <strong>Estudiante:</strong> {{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}
        <strong class="ml-3">CI:</strong> {{ $estudiante->ci }}
    </div>
@stop

@section('content')
<div class="row">
    <!-- Resumen de Asistencias -->
    <div class="col-md-12 mb-3">
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $asistencias->where('estado', 'presente')->count() }}</h3>
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
                        <h3>{{ $asistencias->where('estado', 'ausente')->count() }}</h3>
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
                        <h3>{{ $asistencias->where('estado', 'tarde')->count() }}</h3>
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
                        <h3>{{ $asistencias->where('estado', 'justificado')->count() }}</h3>
                        <p>Justificadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Historial de Asistencias</h3>
                
                <div class="d-flex align-items-center">
                    <label for="filtroMateria" class="mr-2 mb-0">Filtrar por Materia:</label>
                    <select id="filtroMateria" class="form-control form-control-sm" style="width: 200px;">
                        <option value="">Todas las materias</option>
                        @foreach($asistencias->pluck('asignacion.materia')->unique('id')->sortBy('nombre') as $materia)
                            <option value="{{ $materia->nombre }}">{{ $materia->nombre }}</option>
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

                @if($asistencias->count() > 0)
                    <div class="table-responsive">
                        <table id="asistenciasTable" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Día</th>
                                    <th>Materia</th>
                                    <th>Docente</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistencias as $asistencia)
                                    <tr>
                                        <td>{{ $asistencia->fecha_formateada }}</td>
                                        <td>{{ $asistencia->dia_semana }}</td>
                                        <td><span class="badge badge-info">{{ $asistencia->asignacion->materia->nombre }}</span></td>
                                        <td>{{ $asistencia->asignacion->docente ? $asistencia->asignacion->docente->nombre . ' ' . $asistencia->asignacion->docente->paterno : 'Sin docente' }}</td>
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
                                                <small class="text-muted">{{ $asistencia->observaciones }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $asistencias->links() }}
                    </div>

                    <!-- Estadísticas detalladas -->
                    @if($asistencias->count() > 0)
                        @php
                            $totalAsistencias = $asistencias->count();
                            $presentes = $asistencias->where('estado', 'presente')->count();
                            $tardes = $asistencias->where('estado', 'tarde')->count();
                            $porcentajeAsistencia = $totalAsistencias > 0 ? round((($presentes + $tardes) / $totalAsistencias) * 100, 2) : 0;
                        @endphp
                        
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Estadísticas Generales</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Total de registros:</strong> {{ $totalAsistencias }}</p>
                                            <p><strong>Porcentaje de asistencia:</strong> 
                                                <span class="badge badge-{{ $porcentajeAsistencia >= 80 ? 'success' : ($porcentajeAsistencia >= 60 ? 'warning' : 'danger') }}">
                                                    {{ $porcentajeAsistencia }}%
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            @if($porcentajeAsistencia >= 80)
                                                <div class="alert alert-success">
                                                    <i class="fas fa-trophy mr-2"></i>¡Excelente asistencia!
                                                </div>
                                            @elseif($porcentajeAsistencia >= 60)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>Asistencia regular. Puedes mejorar.
                                                </div>
                                            @else
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>Asistencia baja. Necesitas mejorar.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info text-center">
                        <h4><i class="fas fa-info-circle"></i> Sin registros de asistencia</h4>
                        <p>Aún no tienes registros de asistencia en el sistema.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
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
        .badge {
            font-size: 0.8em;
            padding: 0.375rem 0.75rem;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var hasData = {{ $asistencias->count() > 0 ? 'true' : 'false' }};

        if (hasData) {
            var table = $('#asistenciasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "pageLength": 15,
                "order": [[0, "desc"]], // Ordenar por fecha descendente
                "columnDefs": [
                    { "type": "date", "targets": 0 }
                ],
                "paging": false, // Desactivar paginación de DataTables para usar la de Laravel
                "info": false
            });

            // Filtro por materia
            $('#filtroMateria').on('change', function() {
                var materia = this.value;
                if (materia) {
                    table.column(2).search(materia).draw();
                } else {
                    table.column(2).search('').draw();
                }
            });
        }
    });
</script>
@stop