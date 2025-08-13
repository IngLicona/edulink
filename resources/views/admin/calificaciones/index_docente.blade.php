@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Calificaciones</h1>
                <p class="text-muted">Docente: {{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mis Calificaciones</li>
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
                        <h3 class="card-title">Mis Asignaciones Activas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Gestión</th>
                                        <th>Nivel</th>
                                        <th>Grado</th>
                                        <th>Paralelo</th>
                                        <th>Materia</th>
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
                                        <td>{{ $asignacion->turno->nombre }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.calificaciones.show_admin', $asignacion->id) }}" 
                                                   class="btn btn-info btn-sm" title="Ver Calificaciones">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" 
                                                   class="btn btn-success btn-sm" title="Registrar Calificaciones">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No tienes asignaciones activas en este momento.</p>
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
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Asignaciones</span>
                                <span class="info-box-number">{{ $asignaciones->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Materias</span>
                                <span class="info-box-number">{{ $asignaciones->pluck('materia.nombre')->unique()->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-graduation-cap"></i></span>
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
    @if($asignaciones->count() > 0)
    $('.table').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[ 1, "desc" ], [ 2, "asc" ]]
    });
    @endif
});
</script>
@endsection