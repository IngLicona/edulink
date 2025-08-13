@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Calificaciones</h1>
                <p class="text-muted">{{ $asignacion->materia->nombre }} - {{ $asignacion->nivel->nombre }} {{ $asignacion->grado->nombre }}"{{ $asignacion->paralelo->nombre }}"</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.calificaciones.index') }}">Calificaciones</a></li>
                    <li class="breadcrumb-item active">Ver Calificaciones</li>
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

                <!-- Información de la asignación -->
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Información de la Asignación
                        </h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Nueva Calificación
                            </a>
                            <a href="{{ route('admin.calificaciones.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Gestión:</strong> {{ $asignacion->gestion->nombre }}
                            </div>
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
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <strong>Materia:</strong> {{ $asignacion->materia->nombre }}
                            </div>
                            <div class="col-md-4">
                                <strong>Docente:</strong> {{ $asignacion->docente->nombre }} {{ $asignacion->docente->paterno }}
                            </div>
                            <div class="col-md-4">
                                <strong>Turno:</strong> {{ $asignacion->turno->nombre }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calificaciones por período -->
                @foreach($periodos as $periodo)
                    @php
                        $calificacionesPeriodo = $calificaciones->where('periodo_id', $periodo->id);
                    @endphp
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ $periodo->nombre }}
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">{{ $calificacionesPeriodo->count() }} evaluaciones</span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($calificacionesPeriodo->count() > 0)
                                @foreach($calificacionesPeriodo as $calificacion)
                                <div class="card card-outline card-secondary mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <strong>{{ $calificacion->tipo }}</strong>
                                            @if($calificacion->descripcion)
                                                - {{ $calificacion->descripcion }}
                                            @endif
                                        </h5>
                                        <div class="card-tools">
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($calificacion->fecha)->format('d/m/Y') }}</small>
                                            <div class="btn-group ml-2">
                                                <a href="{{ route('admin.calificaciones.edit', $calificacion->id) }}" 
                                                   class="btn btn-warning btn-xs" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-xs" 
                                                        onclick="confirmarEliminacion({{ $calificacion->id }})" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Estudiante</th>
                                                        <th width="15%" class="text-center">Calificación</th>
                                                        <th width="15%" class="text-center">Estado</th>
                                                        <th width="10%" class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($calificacion->detalleCalificaciones->sortBy('estudiante.paterno') as $detalle)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $detalle->estudiante->paterno }} {{ $detalle->estudiante->materno }}, {{ $detalle->estudiante->nombre }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-{{ $detalle->nota >= 60 ? 'success' : 'danger' }} badge-lg">
                                                                {{ number_format($detalle->nota, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($detalle->nota >= 60)
                                                                <i class="fas fa-check-circle text-success" title="Aprobado"></i>
                                                            @else
                                                                <i class="fas fa-times-circle text-danger" title="Reprobado"></i>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.calificaciones.show_estudiante', [$asignacion->id, $detalle->estudiante->id]) }}" 
                                                               class="btn btn-info btn-xs" title="Ver detalle">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="thead-dark">
                                                    <tr>
                                                        <th colspan="2">Promedio del grupo</th>
                                                        <th class="text-center">
                                                            @php
                                                                $promedioGrupo = $calificacion->detalleCalificaciones->avg('nota');
                                                            @endphp
                                                            <span class="badge badge-{{ $promedioGrupo >= 60 ? 'success' : 'danger' }} badge-lg">
                                                                {{ number_format($promedioGrupo, 2) }}
                                                            </span>
                                                        </th>
                                                        <th class="text-center">
                                                            @php
                                                                $aprobados = $calificacion->detalleCalificaciones->where('nota', '>=', 60)->count();
                                                                $total = $calificacion->detalleCalificaciones->count();
                                                            @endphp
                                                            {{ $aprobados }}/{{ $total }}
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No hay calificaciones registradas para este período.</p>
                                    <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Registrar Primera Calificación
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($calificaciones->count() == 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                        <h4>No hay calificaciones registradas</h4>
                        <p class="text-muted">Comience registrando la primera calificación para esta asignación.</p>
                        <a href="{{ route('admin.calificaciones.create', $asignacion->id) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Registrar Primera Calificación
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar esta calificación?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="eliminarForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmarEliminacion(calificacionId) {
    $('#eliminarForm').attr('action', '/admin/calificaciones/' + calificacionId);
    $('#eliminarModal').modal('show');
}

$(document).ready(function() {
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection

@section('styles')
<style>
.badge-lg {
    font-size: 1em;
    padding: 0.5em 0.75em;
}

.card-outline.card-secondary {
    border-top: 3px solid #6c757d;
}
</style>
@endsection