@extends('adminlte::page')

@section('content_header')
    <h1>Editar Asistencia</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.asistencias.index') }}">Asistencias</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.asistencias.show', $asistencia->asignacion->id) }}">Detalles</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- Información del registro -->
        <div class="card card-outline card-info mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información del Registro</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Estudiante:</strong><br>
                           <span class="h6">{{ $asistencia->estudiante->nombre . ' ' . $asistencia->estudiante->paterno . ' ' . $asistencia->estudiante->materno }}</span>
                        </p>
                        <p><strong>CI:</strong> {{ $asistencia->estudiante->ci }}</p>
                        <p><strong>Fecha:</strong> <span class="badge badge-primary">{{ $asistencia->fecha_formateada }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Materia:</strong> <span class="badge badge-info">{{ $asistencia->asignacion->materia->nombre }}</span></p>
                        <p><strong>Docente:</strong> {{ $asistencia->asignacion->docente ? $asistencia->asignacion->docente->nombre . ' ' . $asistencia->asignacion->docente->paterno : 'Sin docente' }}</p>
                        <p><strong>Curso:</strong> {{ $asistencia->asignacion->nivel->nombre }} - {{ $asistencia->asignacion->grado->nombre }} "{{ $asistencia->asignacion->paralelo->nombre }}"</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de edición -->
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Editar Asistencia</h3>
            </div>

            <form action="{{ route('admin.asistencias.update', $asistencia->id) }}" method="POST">
                @csrf
                @method('PUT')

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

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="estado">Estado de Asistencia *</label>
                        <select id="estado" name="estado" class="form-control form-control-lg @error('estado') is-invalid @enderror" required>
                            <option value="presente" {{ $asistencia->estado == 'presente' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle text-success"></i> Presente
                            </option>
                            <option value="ausente" {{ $asistencia->estado == 'ausente' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle text-danger"></i> Ausente
                            </option>
                            <option value="tarde" {{ $asistencia->estado == 'tarde' ? 'selected' : '' }}>
                                <i class="fas fa-clock text-warning"></i> Tarde
                            </option>
                            <option value="justificado" {{ $asistencia->estado == 'justificado' ? 'selected' : '' }}>
                                <i class="fas fa-file-medical text-info"></i> Justificado
                            </option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" 
                                  name="observaciones" 
                                  class="form-control @error('observaciones') is-invalid @enderror" 
                                  rows="3" 
                                  maxlength="500"
                                  placeholder="Ingrese observaciones adicionales (opcional)">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
                        <small class="form-text text-muted">
                            <span id="contador">{{ strlen($asistencia->observaciones ?? '') }}</span>/500 caracteres
                        </small>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vista previa del estado -->
                    <div class="form-group">
                        <label>Vista Previa:</label>
                        <div id="vistaPrevia" class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $asistencia->estudiante->nombre . ' ' . $asistencia->estudiante->paterno }}</strong>
                                    <br><small class="text-muted">{{ $asistencia->fecha_formateada }}</small>
                                </div>
                                <div id="estadoBadge">
                                    <!-- Se actualizará dinámicamente -->
                                </div>
                            </div>
                            <div id="observacionesPreview" class="mt-2" style="display: none;">
                                <small class="text-muted"><strong>Obs:</strong> <span id="obsTexto"></span></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.asistencias.show', $asistencia->asignacion->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                    <div>
                        @can('admin.asistencias.delete')
                            <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#modalEliminar">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        @endcan
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
@can('admin.asistencias.delete')
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este registro de asistencia?</p>
                <div class="alert alert-warning">
                    <strong>Estudiante:</strong> {{ $asistencia->estudiante->nombre . ' ' . $asistencia->estudiante->paterno }}<br>
                    <strong>Fecha:</strong> {{ $asistencia->fecha_formateada }}<br>
                    <strong>Estado actual:</strong> 
                    @switch($asistencia->estado)
                        @case('presente')
                            <span class="badge badge-success">PRESENTE</span>
                            @break
                        @case('ausente')
                            <span class="badge badge-danger">AUSENTE</span>
                            @break
                        @case('tarde')
                            <span class="badge badge-warning">TARDE</span>
                            @break
                        @case('justificado')
                            <span class="badge badge-info">JUSTIFICADO</span>
                            @break
                    @endswitch
                </div>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.asistencias.destroy', $asistencia->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@stop

@section('css')
    <style>
        .card-outline {
            border-top: 3px solid;
        }
        .card-outline.card-info {
            border-top-color: #17a2b8;
        }
        .card-outline.card-warning {
            border-top-color: #ffc107;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.375rem 0.75rem;
        }
        #vistaPrevia {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        .form-control-lg {
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }
    </style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Contador de caracteres para observaciones
        $('#observaciones').on('input', function() {
            var length = $(this).val().length;
            $('#contador').text(length);
            
            if (length > 450) {
                $('#contador').addClass('text-warning');
            } else if (length > 480) {
                $('#contador').addClass('text-danger').removeClass('text-warning');
            } else {
                $('#contador').removeClass('text-warning text-danger');
            }
            
            actualizarVistaPrevia();
        });

        // Actualizar vista previa cuando cambia el estado
        $('#estado').on('change', function() {
            actualizarVistaPrevia();
        });

        // Función para actualizar la vista previa
        function actualizarVistaPrevia() {
            var estado = $('#estado').val();
            var observaciones = $('#observaciones').val().trim();
            var badge = '';

            switch(estado) {
                case 'presente':
                    badge = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> PRESENTE</span>';
                    break;
                case 'ausente':
                    badge = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> AUSENTE</span>';
                    break;
                case 'tarde':
                    badge = '<span class="badge badge-warning"><i class="fas fa-clock"></i> TARDE</span>';
                    break;
                case 'justificado':
                    badge = '<span class="badge badge-info"><i class="fas fa-file-medical"></i> JUSTIFICADO</span>';
                    break;
            }

            $('#estadoBadge').html(badge);

            if (observaciones) {
                $('#obsTexto').text(observaciones);
                $('#observacionesPreview').show();
            } else {
                $('#observacionesPreview').hide();
            }
        }

        // Inicializar vista previa
        actualizarVistaPrevia();

        // Confirmación antes de enviar
        $('form').on('submit', function(e) {
            if (!confirm('¿Está seguro de que desea guardar los cambios?')) {
                e.preventDefault();
            }
        });
    });
</script>
@stop