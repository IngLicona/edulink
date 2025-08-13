@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Calificaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Calificaciones</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
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

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Asignaciones Activas
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-control" id="filtroGestion">
                                    <option value="">Todas las Gestiones</option>
                                    @foreach($asignaciones->pluck('gestion.nombre')->unique() as $gestion)
                                        <option value="{{ $gestion }}">{{ $gestion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="filtroNivel">
                                    <option value="">Todos los Niveles</option>
                                    @foreach($asignaciones->pluck('nivel.nombre')->unique() as $nivel)
                                        <option value="{{ $nivel }}">{{ $nivel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="filtroMateria">
                                    <option value="">Todas las Materias</option>
                                    @foreach($asignaciones->pluck('materia.nombre')->unique() as $materia)
                                        <option value="{{ $materia }}">{{ $materia }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary btn-block" id="limpiarFiltros">
                                    <i class="fas fa-broom"></i> Limpiar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="calificacionesTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Gestión</th>
                                        <th>Nivel</th>
                                        <th>Grado</th>
                                        <th>Paralelo</th>
                                        <th>Materia</th>
                                        <th>Docente</th>
                                        <th>Turno</th>
                                        <th width="15%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asignaciones as $asignacion)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span>
                                        </td>
                                        <td>{{ $asignacion->nivel->nombre }}</td>
                                        <td>{{ $asignacion->grado->nombre }}</td>
                                        <td>{{ $asignacion->paralelo->nombre }}</td>
                                        <td>
                                            <strong>{{ $asignacion->materia->nombre }}</strong>
                                        </td>
                                        <td>{{ $asignacion->docente->nombre }} {{ $asignacion->docente->paterno }}</td>
                                        <td>{{ $asignacion->turno->nombre }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.calificaciones.show_admin', $asignacion->id) }}" 
                                                   class="btn btn-info btn-sm" title="Ver Calificaciones">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" 
                                                   class="btn btn-success btn-sm" title="Registrar Calificación">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('admin.calificaciones.reporte', $asignacion->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Generar Reporte" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No hay asignaciones activas registradas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($asignaciones->count() > 0)
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Asignaciones</span>
                                <span class="info-box-number">{{ $asignaciones->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Materias</span>
                                <span class="info-box-number">{{ $asignaciones->pluck('materia.nombre')->unique()->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Docentes</span>
                                <span class="info-box-number">{{ $asignaciones->pluck('docente_id')->unique()->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Niveles</span>
                                <span class="info-box-number">{{ $asignaciones->pluck('nivel.nombre')->unique()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#calificacionesTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[ 1, "desc" ], [ 2, "asc" ], [ 3, "asc" ]]
    });

    // Filtros personalizados
    $('#filtroGestion').on('change', function() {
        table.column(1).search(this.value).draw();
    });

    $('#filtroNivel').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    $('#filtroMateria').on('change', function() {
        table.column(5).search(this.value).draw();
    });

    // Limpiar filtros
    $('#limpiarFiltros').on('click', function() {
        $('#filtroGestion, #filtroNivel, #filtroMateria').val('');
        table.search('').columns().search('').draw();
    });
});
</script>
@endsection