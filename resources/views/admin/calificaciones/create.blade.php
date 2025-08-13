@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Registrar Calificaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.calificaciones.index') }}">Calificaciones</a></li>
                    <li class="breadcrumb-item active">Registrar</li>
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
                            <i class="fas fa-plus mr-2"></i>
                            Nueva Calificación
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.calificaciones.show_admin', $asignacion->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Información de la asignación -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Información de la Asignación</h5>
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
                            </div>
                        </div>

                        <!-- Formulario de calificaciones -->
                        <form action="{{ route('admin.calificaciones.store') }}" method="POST" id="calificacionesForm">
                            @csrf
                            <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="periodo_id">Periodo <span class="text-danger">*</span></label>
                                        <select class="form-control" name="periodo_id" id="periodo_id" required>
                                            <option value="">Seleccione un periodo</option>
                                            @foreach($periodos as $periodo)
                                                <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                                    {{ $periodo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo">Tipo de Evaluación <span class="text-danger">*</span></label>
                                        <select class="form-control" name="tipo" id="tipo" required>
                                            <option value="">Seleccione el tipo</option>
                                            <option value="Examen" {{ old('tipo') == 'Examen' ? 'selected' : '' }}>Examen</option>
                                            <option value="Tarea" {{ old('tipo') == 'Tarea' ? 'selected' : '' }}>Tarea</option>
                                            <option value="Proyecto" {{ old('tipo') == 'Proyecto' ? 'selected' : '' }}>Proyecto</option>
                                            <option value="Participación" {{ old('tipo') == 'Participación' ? 'selected' : '' }}>Participación</option>
                                            <option value="Práctica" {{ old('tipo') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                            <option value="Quiz" {{ old('tipo') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                            <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea class="form-control" name="descripcion" id="descripcion" rows="2" placeholder="Descripción opcional de la evaluación">{{ old('descripcion') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de estudiantes -->
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-users mr-2"></i>
                                        Estudiantes Matriculados ({{ $estudiantes->count() }})
                                    </h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-sm btn-info" id="llenarNotasBtn">
                                            <i class="fas fa-fill"></i> Llenar todas las notas
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($estudiantes->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="10%">CI</th>
                                                    <th>Estudiante</th>
                                                    <th width="15%" class="text-center">Calificación (0-100) <span class="text-danger">*</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($estudiantes as $estudiante)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $estudiante->ci }}</td>
                                                    <td>
                                                        <strong>{{ $estudiante->paterno }} {{ $estudiante->materno }}, {{ $estudiante->nombre }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control calificacion-input" 
                                                               name="calificaciones[{{ $estudiante->id }}]" 
                                                               min="0" 
                                                               max="100" 
                                                               step="0.01" 
                                                               value="{{ old('calificaciones.' . $estudiante->id) }}" 
                                                               placeholder="0.00"
                                                               required>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay estudiantes matriculados en esta asignación.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($estudiantes->count() > 0)
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Calificaciones
                                </button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para llenar notas automáticamente -->
<div class="modal fade" id="llenarNotasModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Llenar todas las notas</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="notaGlobal">Nota para todos los estudiantes:</label>
                    <input type="number" class="form-control" id="notaGlobal" min="0" max="100" step="0.01" placeholder="Ej: 85.50">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="aplicarNotaGlobal">Aplicar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación en tiempo real de las calificaciones
    $('.calificacion-input').on('input', function() {
        let valor = parseFloat($(this).val());
        let input = $(this);
        
        // Remover clases previas
        input.removeClass('is-valid is-invalid');
        
        if (isNaN(valor)) {
            return;
        }
        
        if (valor < 0 || valor > 100) {
            input.addClass('is-invalid');
        } else {
            input.addClass('is-valid');
        }
        
        // Cambiar color de fondo según aprobación
        if (valor >= 60) {
            input.css('background-color', '#d4edda');
        } else {
            input.css('background-color', '#f8d7da');
        }
    });

    // Llenar notas automáticamente
    $('#llenarNotasBtn').click(function() {
        $('#llenarNotasModal').modal('show');
    });

    $('#aplicarNotaGlobal').click(function() {
        let nota = $('#notaGlobal').val();
        if (nota !== '' && nota >= 0 && nota <= 100) {
            $('.calificacion-input').val(nota).trigger('input');
            $('#llenarNotasModal').modal('hide');
            $('#notaGlobal').val('');
        } else {
            alert('Por favor ingrese una nota válida entre 0 y 100');
        }
    });

    // Validación antes de enviar
    $('#calificacionesForm').on('submit', function(e) {
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
                alert(`Hay ${emptyFields} calificaciones sin completar. Por favor complete todas las calificaciones.`);
            } else {
                alert('Hay calificaciones con valores inválidos. Las notas deben estar entre 0 y 100.');
            }
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.calificacion-input:focus {
    box-shadow: 0 0 5px rgba(0,123,255,.5);
}
</style>
@endsection