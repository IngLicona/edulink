@extends('adminlte::page')

@section('content_header')
    <h1>Mis Asignaciones - Registro de Asistencias</h1>
    <div class="alert alert-info">
        <i class="fas fa-user-tie mr-2"></i>
        <strong>Docente:</strong> {{ $docente->nombre . ' ' . $docente->paterno . ' ' . $docente->materno }}
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <label for="mostrar" class="mr-2 mb-0">Mostrar</label>
                    <select id="mostrar" class="form-control form-control-sm" style="width: auto;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="ml-2">Asignaciones</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
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

                <div class="table-responsive">
                    <table id="asignacionesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estado</th>
                                <th>Gestión</th>
                                <th>Curso</th>
                                <th>Materia</th>
                                <th>Turno</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asignaciones as $index => $asignacion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($asignacion->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span></td>
                                    <td>
                                        <strong>{{ $asignacion->nivel->nombre }}</strong><br>
                                        {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"
                                    </td>
                                    <td><span class="badge badge-info">{{ $asignacion->materia->nombre }}</span></td>
                                    <td><span class="badge badge-warning">{{ $asignacion->turno->nombre }}</span></td>
                                    <td>{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y') : 'No registrada' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.asistencias.create', $asignacion->id) }}" 
                                               class="btn btn-success btn-sm" 
                                               title="Registrar Asistencia">
                                                <i class="fas fa-calendar-check"></i>
                                            </a>

                                            <a href="{{ route('admin.asistencias.show', $asignacion->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Ver Asistencias">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <button type="button" 
                                                    class="btn btn-warning btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#modalReporte{{ $asignacion->id }}"
                                                    title="Generar Reporte">
                                                <i class="fas fa-chart-bar"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="alert alert-warning mb-0">
                                            <h5><i class="fas fa-exclamation-triangle"></i> No hay asignaciones</h5>
                                            <p class="mb-0">No tienes asignaciones activas para registrar asistencias.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales para Reportes -->
@foreach($asignaciones as $asignacion)
    <div class="modal fade" id="modalReporte{{ $asignacion->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Reporte de Asistencias</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.asistencias.reporte') }}" method="GET" target="_blank">
                    <div class="modal-body">
                        <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">
                        
                        <div class="card card-outline card-info mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Mi Asignación</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Curso:</strong> {{ $asignacion->nivel->nombre }} - {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
                                <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                                <p class="mb-0"><strong>Turno:</strong> {{ $asignacion->turno->nombre }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" class="form-control" 
                                   value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Fecha de Fin *</label>
                            <input type="date" name="fecha_fin" class="form-control" 
                                   value="{{ Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-chart-bar"></i> Generar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <style>
        .btn-group .btn {
            margin-right: 2px;
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
    </style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var hasData = {{ count($asignaciones) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            table = $('#asignacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 }
                ]
            });

            $('.dataTables_length, .dataTables_filter').hide();

            $('#mostrar').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $('#buscar').on('keyup', function() {
                table.search(this.value).draw();
            });
        } else {
            $('#buscar, #mostrar').prop('disabled', true);
        }
    });
</script>
@stop