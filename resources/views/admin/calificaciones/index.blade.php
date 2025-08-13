@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Asignaciones para Registro de Calificaciones</h1>
    <hr>
@stop

@se    <!-- Modales para Reportes -->
@foreach($asignaciones as $asignacion)
    @can('admin.calificaciones.reporte')
    <div class="modal fade" id="modalReporte{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="modalReporteLabel{{ $asignacion->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalReporteLabel{{ $asignacion->id }}">Generar Reporte de Calificaciones</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>ent')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('admin.calificaciones.index')
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
                @endcan
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

                <!-- Botones de exportación -->
                @can('admin.calificaciones.reporte')
                <div class="mb-3">
                    <button class="btn btn-secondary btn-sm" id="copiar">
                        <i class="fas fa-copy"></i> COPIAR
                    </button>
                    <button class="btn btn-danger btn-sm" id="pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button class="btn btn-info btn-sm" id="csv">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="btn btn-success btn-sm" id="excel">
                        <i class="fas fa-file-excel"></i> EXCEL
                    </button>
                    <button class="btn btn-warning btn-sm" id="imprimir">
                        <i class="fas fa-print"></i> IMPRIMIR
                    </button>
                </div>
                @endcan

                <div class="table-responsive">
                    <table id="asignacionesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estado</th>
                                <th>Docente</th>
                                <th>Gestión</th>
                                <th>Nivel</th>
                                <th>Grado</th>
                                <th>Paralelo</th>
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
                                    <td>{{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno . ' ' . $asignacion->docente->materno : 'Sin docente' }}</td>
                                    <td><span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span></td>
                                    <td>{{ $asignacion->nivel->nombre }}</td>
                                    <td>{{ $asignacion->grado->nombre }}</td>
                                    <td>{{ $asignacion->paralelo->nombre }}</td>
                                    <td><span class="badge badge-info">{{ $asignacion->materia->nombre }}</span></td>
                                    <td><span class="badge badge-warning">{{ $asignacion->turno->nombre }}</span></td>
                                    <td>{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y') : 'No registrada' }}</td>
                                    <td class="text-center">
                                        @if(auth()->user()->hasAnyPermission(['admin.calificaciones.create', 'admin.calificaciones.store']))
                                            <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" 
                                               class="btn btn-success btn-sm mr-1" 
                                               title="Registrar Calificación">
                                                <i class="fas fa-edit"></i> Calificar
                                            </a>
                                        @endif

                                        @if(auth()->user()->hasAnyPermission(['admin.calificaciones.show_admin', 'admin.calificaciones.show']))
                                            <a href="{{ route('admin.calificaciones.show_admin', $asignacion->id) }}" 
                                               class="btn btn-info btn-sm mr-1" 
                                               title="Ver Calificaciones">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        @endif

                                        @if(auth()->user()->hasAnyPermission(['admin.calificaciones.reporte', 'admin.calificaciones.generar-reporte']))
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#modalReporte{{ $asignacion->id }}"
                                                    title="Generar Reporte">
                                                <i class="fas fa-chart-bar"></i> Reporte
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        No hay asignaciones registradas.
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
    <div class="modal fade" id="modalReporte{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="modalReporteLabel{{ $asignacion->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalReporteLabel{{ $asignacion->id }}">Generar Reporte de Calificaciones</h5>
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
                                <p><strong>Docente:</strong> {{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno : 'Sin docente' }}</p>
                                <p><strong>Curso:</strong> {{ $asignacion->nivel->nombre }} - {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
                                <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                                <p class="mb-0"><strong>Turno:</strong> {{ $asignacion->turno->nombre }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Período *</label>
                            <select name="periodo_id" class="form-control" required>
                                <option value="">Seleccionar período</option>
                                @foreach($periodos ?? [] as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
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
    @endcan
@endforeach
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <style>
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
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
        .table-responsive {
            overflow-x: auto;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        var hasData = {{ count($asignaciones ?? []) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            table = $('#asignacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "dom": 'Bfrtip',
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 }
                ],
                "buttons": [
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'd-none',
                        title: 'Listado de Asignaciones para Calificaciones',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Asignaciones para Calificaciones',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        className: 'd-none',
                        title: 'Listado de Asignaciones para Calificaciones',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            });

            $('.dataTables_length, .dataTables_filter').hide();

            $('#mostrar').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $('#buscar').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#copiar').on('click', function() {
                table.button('.buttons-copy').trigger();
            });

            $('#pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });

            $('#csv').on('click', function() {
                table.button('.buttons-csv').trigger();
            });

            $('#excel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });

            $('#imprimir').on('click', function() {
                table.button('.buttons-print').trigger();
            });
        } else {
            $('#copiar, #pdf, #csv, #excel, #imprimir').prop('disabled', true).addClass('disabled');
            $('#buscar, #mostrar').prop('disabled', true);
        }
    });
</script>
@stop