@extends('adminlte::page')

@section('content_header')
    <h1>Registrar Calificaciones</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.calificaciones.index') }}">Calificaciones</a></li>
            <li class="breadcrumb-item active">Registrar</li>
        </ol>
    </nav>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Información de la Asignación -->
        <div class="card card-outline card-info mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información de la Asignación</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Docente:</strong> {{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno . ' ' . $asignacion->docente->materno : 'Sin docente' }}</p>
                        <p><strong>Gestión:</strong> <span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span></p>
                        <p><strong>Nivel:</strong> {{ $asignacion->nivel->nombre }}</p>
                        <p><strong>Grado:</strong> {{ $asignacion->grado->nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Paralelo:</strong> {{ $asignacion->paralelo->nombre }}</p>
                        <p><strong>Materia:</strong> <span class="badge badge-info">{{ $asignacion->materia->nombre }}</span></p>
                        <p><strong>Turno:</strong> <span class="badge badge-warning">{{ $asignacion->turno->nombre }}</span></p>
                        <p><strong>Estudiantes:</strong> <span class="badge badge-success">{{ $estudiantes->count() }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Calificaciones -->
        <div class="card card-outline card-success">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Registro de Calificaciones</h3>
                <div>
                    <button type="button" class="btn btn-sm btn-info" id="llenarNotasBtn">
                        <i class="fas fa-fill"></i> Llenar Todas
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.calificaciones.store') }}" method="POST" id="calificacionesForm">
                @csrf
                <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">

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

                    <!-- Datos de la evaluación -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="periodo_id">Periodo *</label>
                                <select class="form-control @error('periodo_id') is-invalid @enderror" name="periodo_id" id="periodo_id" required>
                                    <option value="">Seleccione un periodo</option>
                                    @foreach($periodos as $periodo)
                                        <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                            {{ $periodo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('periodo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo">Tipo de Evaluación *</label>
                                <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo" required>
                                    <option value="">Seleccione el tipo</option>
                                    <option value="Examen" {{ old('tipo') == 'Examen' ? 'selected' : '' }}>Examen</option>
                                    <option value="Tarea" {{ old('tipo') == 'Tarea' ? 'selected' : '' }}>Tarea</option>
                                    <option value="Proyecto" {{ old('tipo') == 'Proyecto' ? 'selected' : '' }}>Proyecto</option>
                                    <option value="Participación" {{ old('tipo') == 'Participación' ? 'selected' : '' }}>Participación</option>
                                    <option value="Práctica" {{ old('tipo') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                    <option value="Quiz" {{ old('tipo') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha">Fecha *</label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion" value="{{ old('descripcion') }}" maxlength="255" placeholder="Descripción opcional">
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if($estudiantes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;">Nro</th>
                                        <th>Estudiante</th>
                                        <th>CI</th>
                                        <th style="width: 150px;">Calificación (0-100)*</th>
                                        <th style="width: 80px;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estudiantes as $index => $estudiante)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}</strong>
                                            </td>
                                            <td>{{ $estudiante->ci }}</td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm calificacion-input" 
                                                       name="calificaciones[{{ $estudiante->id }}]" 
                                                       min="0" 
                                                       max="100" 
                                                       step="0.01" 
                                                       value="{{ old('calificaciones.' . $estudiante->id) }}" 
                                                       placeholder="0.00"
                                                       required>
                                            </td>
                                            <td class="text-center">
                                                <span class="estado-badge"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle mr-2"></i>Información de Calificación:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aprobado</span> - Nota >= 60 puntos
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Reprobado</span> - Nota < 60 puntos
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <h4><i class="fas fa-exclamation-triangle"></i> No hay estudiantes matriculados</h4>
                            <p>No se encontraron estudiantes matriculados para esta asignación.</p>
                            <a href="{{ route('admin.calificaciones.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    @endif
                </div>

                @if($estudiantes->count() > 0)
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.calificaciones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Calificaciones
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Modal para llenar notas automáticamente -->
<div class="modal fade" id="llenarNotasModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Llenar todas las notas</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="notaGlobal">Nota para todos los estudiantes (0-100):</label>
                    <input type="number" class="form-control" id="notaGlobal" min="0" max="100" step="0.01" placeholder="Ej: 85.50">
                    <small class="form-text text-muted">Esta nota se aplicará a todos los estudiantes de la lista.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="aplicarNotaGlobal">Aplicar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .card-outline {
            border-top: 3px solid;
        }
        .card-outline.card-info {
            border-top-color: #17a2b8;
        }
        .card-outline.card-success {
            border-top-color: #28a745;
        }
        .calificacion-input {
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .calificacion-input:focus {
            box-shadow: 0 0 5px rgba(0,123,255,.5);
        }
        .badge {
            font-size: 0.9em;
            padding: 0.375rem 0.75rem;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .estado-badge {
            display: inline-block;
            min-width: 20px;
        }
    </style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Validación en tiempo real de las calificaciones
        $('.calificacion-input').on('input', function() {
            let valor = parseFloat($(this).val());
            let input = $(this);
            let estadoBadge = $(this).closest('tr').find('.estado-badge');
            
            // Remover clases previas
            input.removeClass('is-valid is-invalid border-success border-danger');
            estadoBadge.html('');
            
            if (isNaN(valor) || $(this).val() === '') {
                input.css('background-color', '');
                return;
            }
            
            if (valor < 0 || valor > 100) {
                input.addClass('is-invalid border-danger');
                input.css('background-color', '#f8d7da');
                estadoBadge.html('<span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Inválida</span>');
            } else {
                input.addClass('is-valid');
                
                // Cambiar color y estado según aprobación
                if (valor >= 60) {
                    input.addClass('border-success');
                    input.css('background-color', '#d4edda');
                    estadoBadge.html('<span class="badge badge-success"><i class="fas fa-check-circle"></i> Aprobado</span>');
                } else {
                    input.addClass('border-warning');
                    input.css('background-color', '#fff3cd');
                    estadoBadge.html('<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Reprobado</span>');
                }
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
                
                // Mostrar mensaje de confirmación
                toastr.success('Se han aplicado las notas a todos los estudiantes');
            } else {
                alert('Por favor ingrese una nota válida entre 0 y 100');
            }
        });

        // Validación antes de enviar
        $('#calificacionesForm').on('submit', function(e) {
            let valid = true;
            let emptyFields = 0;
            let invalidFields = 0;
            
            $('.calificacion-input').each(function() {
                let valor = $(this).val();
                if (valor === '') {
                    valid = false;
                    emptyFields++;
                    $(this).addClass('is-invalid border-danger');
                } else if (valor < 0 || valor > 100) {
                    valid = false;
                    invalidFields++;
                    $(this).addClass('is-invalid border-danger');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                if (emptyFields > 0) {
                    alert(`Hay ${emptyFields} calificaciones sin completar. Por favor complete todas las calificaciones.`);
                } else if (invalidFields > 0) {
                    alert(`Hay ${invalidFields} calificaciones con valores inválidos. Las notas deben estar entre 0 y 100.`);
                }
                
                // Scroll al primer campo inválido
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            } else {
                // Mostrar confirmación antes de enviar
                if (!confirm('¿Está seguro de que desea guardar estas calificaciones?')) {
                    e.preventDefault();
                }
            }
        });

        // Inicializar tooltips
        $('[title]').tooltip();
    });
</script>
@stop