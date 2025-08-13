@extends('adminlte::page')

@section('content_header')
    <h1>Registrar Asistencia</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.asistencias.index') }}">Asistencias</a></li>
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
                        <p><strong>Fecha:</strong> <span class="badge badge-success">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Asistencia -->
        <div class="card card-outline card-success">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-2"></i>Registro de Asistencia</h3>
                <div>
                    <button type="button" class="btn btn-sm btn-success" id="marcarTodosPresentes">
                        <i class="fas fa-check-circle"></i> Todos Presentes
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" id="marcarTodosAusentes">
                        <i class="fas fa-times-circle"></i> Todos Ausentes
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.asistencias.store') }}" method="POST">
                @csrf
                <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">
                <input type="hidden" name="fecha" value="{{ $fecha }}">

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

                    <!-- Selector de Fecha -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechaAsistencia">Fecha de Asistencia *</label>
                                <input type="date" id="fechaAsistencia" class="form-control" 
                                       value="{{ $fecha }}" onchange="cambiarFecha(this.value)">
                            </div>
                        </div>
                        <div class="col-md-8 d-flex align-items-end">
                            @if($asistenciasExistentes->count() > 0)
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Ya existe un registro de asistencia para esta fecha. Los cambios sobrescribirán el registro anterior.
                                </div>
                            @endif
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
                                        <th style="width: 200px;">Estado</th>
                                        <th style="width: 250px;">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estudiantes as $index => $estudiante)
                                        @php
                                            $asistenciaExistente = $asistenciasExistentes->get($estudiante->id);
                                            $estadoActual = $asistenciaExistente ? $asistenciaExistente->estado : 'presente';
                                            $observacionesActuales = $asistenciaExistente ? $asistenciaExistente->observaciones : '';
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno }}</strong>
                                                <input type="hidden" name="asistencias[{{ $index }}][estudiante_id]" value="{{ $estudiante->id }}">
                                            </td>
                                            <td>{{ $estudiante->ci }}</td>
                                            <td>
                                                <select name="asistencias[{{ $index }}][estado]" class="form-control form-control-sm estado-select" required>
                                                    <option value="presente" {{ $estadoActual == 'presente' ? 'selected' : '' }}>
                                                        <i class="fas fa-check-circle text-success"></i> Presente
                                                    </option>
                                                    <option value="ausente" {{ $estadoActual == 'ausente' ? 'selected' : '' }}>
                                                        <i class="fas fa-times-circle text-danger"></i> Ausente
                                                    </option>
                                                    <option value="tarde" {{ $estadoActual == 'tarde' ? 'selected' : '' }}>
                                                        <i class="fas fa-clock text-warning"></i> Tarde
                                                    </option>
                                                    <option value="justificado" {{ $estadoActual == 'justificado' ? 'selected' : '' }}>
                                                        <i class="fas fa-file-medical text-info"></i> Justificado
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="asistencias[{{ $index }}][observaciones]" 
                                                       class="form-control form-control-sm" 
                                                       placeholder="Observaciones..." 
                                                       value="{{ $observacionesActuales }}"
                                                       maxlength="255">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle mr-2"></i>Leyenda de Estados:</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Presente</span> - Asistió puntualmente
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Ausente</span> - No asistió
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Tarde</span> - Llegó tarde
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge badge-info"><i class="fas fa-file-medical"></i> Justificado</span> - Falta justificada
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <h4><i class="fas fa-exclamation-triangle"></i> No hay estudiantes matriculados</h4>
                            <p>No se encontraron estudiantes matriculados para esta asignación.</p>
                            <a href="{{ route('admin.asistencias.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    @endif
                </div>

                @if($estudiantes->count() > 0)
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.asistencias.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Asistencias
                            </button>
                        </div>
                    </div>
                @endif
            </form>
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
        .estado-select {
            border-radius: 4px;
        }
        .estado-select option {
            padding: 5px;
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
    </style>
@stop

@section('js')
<script>
    // Función para cambiar fecha y recargar página
    function cambiarFecha(nuevaFecha) {
        if (nuevaFecha) {
            const url = new URL(window.location);
            url.searchParams.set('fecha', nuevaFecha);
            window.location.href = url.toString();
        }
    }

    // Marcar todos como presentes
    $('#marcarTodosPresentes').on('click', function() {
        $('.estado-select').val('presente');
    });

    // Marcar todos como ausentes
    $('#marcarTodosAusentes').on('click', function() {
        $('.estado-select').val('ausente');
    });

    // Cambiar color del select según el estado
    $(document).ready(function() {
        $('.estado-select').each(function() {
            cambiarColorSelect(this);
        });

        $('.estado-select').on('change', function() {
            cambiarColorSelect(this);
        });
    });

    function cambiarColorSelect(select) {
        const estado = $(select).val();
        $(select).removeClass('border-success border-danger border-warning border-info');
        
        switch(estado) {
            case 'presente':
                $(select).addClass('border-success');
                break;
            case 'ausente':
                $(select).addClass('border-danger');
                break;
            case 'tarde':
                $(select).addClass('border-warning');
                break;
            case 'justificado':
                $(select).addClass('border-info');
                break;
        }
    }

    // Confirmación antes de enviar
    $('form').on('submit', function(e) {
        const totalEstudiantes = $('.estado-select').length;
        const ausentes = $('.estado-select').filter(function() { return $(this).val() === 'ausente'; }).length;
        
        if (ausentes > 0) {
            e.preventDefault();
            if (confirm(`Se registrarán ${ausentes} ausencias de ${totalEstudiantes} estudiantes. ¿Está seguro de continuar?`)) {
                this.submit();
            }
        }
    });
</script>
@stop