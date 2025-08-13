@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Calificaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.calificaciones.index') }}">Calificaciones</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.calificaciones.show_admin', $calificacion->asignacion->id) }}">Ver Calificaciones</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
                            <i class="fas fa-edit mr-2"></i>
                            Editar Calificación
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.calificaciones.show_admin', $calificacion->asignacion->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Información de la asignación y calificación -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Información</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Materia:</strong> {{ $calificacion->asignacion->materia->nombre }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Período:</strong> {{ $calificacion->periodo->nombre }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Tipo:</strong> {{ $calificacion->tipo }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($calificacion->fecha)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <strong>Docente:</strong> {{ $calificacion->asignacion->docente->nombre }} {{ $calificacion->asignacion->docente->paterno }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Grado:</strong> {{ $calificacion->asignacion->nivel->nombre }} {{ $calificacion->asignacion->grado->nombre }}"{{ $calificacion->asignacion->paralelo->nombre }}"
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de edición -->
                        <form action="{{ route('admin.calificaciones.update', $calificacion->id) }}" method="POST" id="editarCalificacionForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo">Tipo de Evaluación <span class="text-danger">*</span></label>
                                        <select class="form-control" name="tipo" id="tipo" required>
                                            <option value="">Seleccione el tipo</option>
                                            <option value="Primer Parcial" {{ $calificacion->tipo == 'Primer Parcial' ? 'selected' : '' }}>Primer Parcial</option>
                                            <option value="Segundo Parcial" {{ $calificacion->tipo == 'Segundo Parcial' ? 'selected' : '' }}>Segundo Parcial</option>
                                            <option value="Examen Final" {{ $calificacion->tipo == 'Examen Final' ? 'selected' : '' }}>Examen Final</option>
                                            <option value="Recuperatorio" {{ $calificacion->tipo == 'Recuperatorio' ? 'selected' : '' }}>Recuperatorio</option>
                                            <option value="Práctico" {{ $calificacion->tipo == 'Práctico' ? 'selected' : '' }}>Práctico</option>
                                            <option value="Proyecto" {{ $calificacion->tipo == 'Proyecto' ? 'selected' : '' }}>Proyecto</option>
                                            <option value="Otro" {{ $calificacion->tipo == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $calificacion->fecha }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion" id="descripcion" 
                                               value="{{ $calificacion->descripcion }}" placeholder="Descripción opcional">
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de estudiantes con calificaciones -->
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-users mr-2"></i>
                                        Calificaciones de Estudiantes ({{ $calificacion->detalleCalificaciones->count() }})
                                    </h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-sm btn-info" id="aplicarNotaBtn">
                                            <i class="fas fa-fill"></i> Aplicar nota a todos
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="10%">CI</th>
                                                    <th>Estudiante</th>
                                                    <th width="15%" class="text-center">Calificación Actual</th>
                                                    <th width="20%" class="text-center">Nueva Calificación (0-100) <span class="text-danger">*</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($calificacion->detalleCalificaciones->sortBy('estudiante.paterno') as $detalle)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $detalle->estudiante->ci }}</td>
                                                    <td>
                                                        <strong>{{ $detalle->estudiante->paterno }} {{ $detalle->estudiante->materno }}, {{ $detalle->estudiante->nombre }}</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-{{ $detalle->nota >= 60 ? 'success' : 'danger' }} badge-lg">
                                                            {{ number_format($detalle->nota, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control calificacion-input" 
                                                               name="calificaciones[{{ $detalle->estudiante->id }}]" 
                                                               min="0" 
                                                               max="100" 
                                                               step="0.01" 
                                                               value="{{ $detalle->nota }}" 
                                                               placeholder="0.00"
                                                               required>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar Calificaciones
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para aplicar nota a todos -->
<div class="modal fade" id="aplicarNotaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aplicar nota a todos los estudiantes</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="notaGlobal">Nota para todos los estudiantes:</label>
                    <input type="number" class="form-control" id="notaGlobal" min="0" max="100" step="0.01" placeholder="Ej: 85.50">
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Esta acción reemplazará todas las calificaciones actuales.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarNotaGlobal">Aplicar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación en tiempo real
    $('.calificacion-input').on('input', function() {
        let valor = parseFloat($(this).val());
        let input = $(this);
        
        input.removeClass('is-valid is-invalid');
        
        if (isNaN(valor)) {
            return;
        }
        
        if (valor < 0 || valor > 100) {
            input.addClass('is-invalid');
        } else {
            input.addClass('is-valid');
        }
        
        // Cambiar color según aprobación
        if (valor >= 60) {
            input.css('background-color', '#d4edda');
        } else {
            input.css('background-color', '#f8d7da');
        }
    });

    // Aplicar nota a todos
    $('#aplicarNotaBtn').click(function() {
        $('#aplicarNotaModal').modal('show');
    });

    $('#confirmarNotaGlobal').click(function() {
        let nota = $('#notaGlobal').val();
        if (nota !== '' && nota >= 0 && nota <= 100) {
            $('.calificacion-input').val(nota).trigger('input');
            $('#aplicarNotaModal').modal('hide');
            $('#notaGlobal').val('');
        } else {
            alert('Por favor ingrese una nota válida entre 0 y 100');
        }
    });

    // Validación antes de enviar
    $('#editarCalificacionForm').on('submit', function(e) {
        let valid = true;
        let emptyFields = 0;
        
        $('.calificacion-input').each(function() {
            let valor = $(this).val();
            if (valor === '' || valor < 0 || valor > 100) {
                valid = false;
                if (valor === '') emptyFields++;
                $(this).addClass('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            if (emptyFields > 0) {
                alert(`Hay ${emptyFields} calificaciones vacías. Complete todas las calificaciones.`);
            } else {
                alert('Hay calificaciones inválidas. Las notas deben estar entre 0 y 100.');
            }
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.badge-lg {
    font-size: 1em;
    padding: 0.5em 0.75em;
}

.calificacion-input:focus {
    box-shadow: 0 0 5px rgba(255,193,7,.5);
}
</style>
@endsection