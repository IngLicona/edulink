@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Matriculaciones</h1>
    <hr>
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
                    <span class="ml-2">Matriculaciones</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                @createButton(['module' => 'matriculaciones'])
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                        Nueva Matriculación
                    </button>
                @endcreateButton
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
                    <table id="matriculacionesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estado</th>
                                <th>Estudiante</th>
                                <th>CI</th>
                                <th>PPFF</th>
                                <th>Gestión</th>
                                <th>Nivel</th>
                                <th>Grado</th>
                                <th>Paralelo</th>
                                <th>Turno</th>
                                <th>Fecha Mat.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matriculaciones as $index => $matriculacion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($matriculacion->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </td>
                                    <td>{{ $matriculacion->estudiante->nombre_completo }}</td>
                                    <td>{{ $matriculacion->estudiante->ci }}</td>
                                    <td>
                                        @if($matriculacion->estudiante->ppff)
                                            <small>{{ $matriculacion->estudiante->ppff->nombre_completo }}</small>
                                        @else
                                            <span class="text-muted">Sin PPFF</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-primary">{{ $matriculacion->gestion->nombre }}</span></td>
                                    <td>{{ $matriculacion->nivel->nombre }}</td>
                                    <td>{{ $matriculacion->grado->nombre }}</td>
                                    <td>{{ $matriculacion->paralelo->nombre }}</td>
                                    <td><span class="badge badge-info">{{ $matriculacion->turno->nombre }}</span></td>
                                    <td>{{ $matriculacion->fecha_matriculacion->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <!-- Botón Matrícula -->
                                        <a href="{{ route('admin.matriculaciones.pdf', $matriculacion->id) }}" 
                                           class="btn btn-warning btn-sm mr-1" 
                                           title="Descargar Matrícula" target="_blank">
                                            <i class="fas fa-file-contract"></i> Matrícula
                                        </a>

                                        @viewButton(['module' => 'matriculaciones'])
                                            <button type="button" class="btn btn-info btn-sm mr-1" 
                                                    data-toggle="modal" data-target="#ModalVer{{ $matriculacion->id }}" 
                                                    title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endviewButton

                                        @editButton(['module' => 'matriculaciones'])
                                            <button type="button" class="btn btn-success btn-sm mr-1" 
                                                    data-toggle="modal" data-target="#ModalUpdate{{ $matriculacion->id }}" 
                                                    title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endeditButton

                                        @deleteButton(['module' => 'matriculaciones'])
                                            <form action="{{ route('admin.matriculaciones.destroy', $matriculacion->id) }}" 
                                                  method="POST" class="form-eliminar d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @enddeleteButton

                                        @noActions(['module' => 'matriculaciones'])
                                            <span class="text-muted small">Sin acciones disponibles</span>
                                        @endnoActions
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">
                                        No hay matriculaciones registradas.
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

<!-- Modales para cada Matriculación -->
@foreach($matriculaciones as $matriculacion)
    <!-- Modal Ver -->
    <div class="modal fade" id="ModalVer{{ $matriculacion->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $matriculacion->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="ModalVerLabel{{ $matriculacion->id }}">Información de la Matriculación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Datos del Estudiante</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nombre Completo:</strong> {{ $matriculacion->estudiante->nombre_completo }}</p>
                                    <p><strong>CI:</strong> {{ $matriculacion->estudiante->ci }}</p>
                                    <p><strong>Fecha de Nacimiento:</strong> {{ $matriculacion->estudiante->fecha_nacimiento ? $matriculacion->estudiante->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                                    <p><strong>Género:</strong> {{ ucfirst($matriculacion->estudiante->genero) }}</p>
                                    <p><strong>Teléfono:</strong> {{ $matriculacion->estudiante->telefono }}</p>
                                    <p><strong>Estado:</strong> 
                                        @if($matriculacion->estudiante->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Datos del PPFF</h6>
                                </div>
                                <div class="card-body">
                                    @if($matriculacion->estudiante->ppff)
                                        <p><strong>Nombre Completo:</strong> {{ $matriculacion->estudiante->ppff->nombre_completo }}</p>
                                        <p><strong>CI:</strong> {{ $matriculacion->estudiante->ppff->ci }}</p>
                                        <p><strong>Parentesco:</strong> {{ ucfirst($matriculacion->estudiante->ppff->parentesco) }}</p>
                                        <p><strong>Ocupación:</strong> {{ $matriculacion->estudiante->ppff->ocupacion }}</p>
                                        <p><strong>Teléfono:</strong> {{ $matriculacion->estudiante->ppff->telefono }}</p>
                                    @else
                                        <p class="text-muted">Sin información de PPFF registrada</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Información Académica</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Gestión:</strong> <span class="badge badge-primary">{{ $matriculacion->gestion->nombre }}</span></p>
                                            <p><strong>Nivel:</strong> {{ $matriculacion->nivel->nombre }}</p>
                                            <p><strong>Grado:</strong> {{ $matriculacion->grado->nombre }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Paralelo:</strong> {{ $matriculacion->paralelo->nombre }}</p>
                                            <p><strong>Turno:</strong> <span class="badge badge-info">{{ $matriculacion->turno->nombre }}</span></p>
                                            <p><strong>Fecha de Matriculación:</strong> {{ $matriculacion->fecha_matriculacion->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.matriculaciones.pdf', $matriculacion->id) }}" 
                       class="btn btn-warning" target="_blank">
                        <i class="fas fa-file-contract"></i> Descargar Matrícula
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="ModalUpdate{{ $matriculacion->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $matriculacion->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="ModalUpdateLabel{{ $matriculacion->id }}">Editar Matriculación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.matriculaciones.update', $matriculacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estudiante *</label>
                                    <select name="estudiante_id" class="form-control" required>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ $matriculacion->estudiante_id == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->nombre_completo }} - CI: {{ $estudiante->ci }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado *</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="activo" {{ $matriculacion->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ $matriculacion->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gestión *</label>
                                    <select name="gestion_id" class="form-control gestion-select" required>
                                        <option value="">Seleccione una gestión</option>
                                        @foreach($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}" {{ $matriculacion->gestion_id == $gestion->id ? 'selected' : '' }}>
                                                {{ $gestion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Turno *</label>
                                    <select name="turno_id" class="form-control" required>
                                        <option value="">Seleccione un turno</option>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id }}" {{ $matriculacion->turno_id == $turno->id ? 'selected' : '' }}>
                                                {{ $turno->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nivel *</label>
                                    <select name="nivel_id" class="form-control nivel-select" required>
                                        <option value="">Seleccione un nivel</option>
                                        @foreach($niveles as $nivel)
                                            <option value="{{ $nivel->id }}" {{ $matriculacion->nivel_id == $nivel->id ? 'selected' : '' }}>
                                                {{ $nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Grado *</label>
                                    <select name="grado_id" class="form-control grado-select" required>
                                        <option value="">Seleccione un grado</option>
                                        @foreach($grados as $grado)
                                            @if($grado->nivel_id == $matriculacion->nivel_id)
                                                <option value="{{ $grado->id }}" {{ $matriculacion->grado_id == $grado->id ? 'selected' : '' }}>
                                                    {{ $grado->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Paralelo *</label>
                                    <select name="paralelo_id" class="form-control paralelo-select" required>
                                        <option value="">Seleccione un paralelo</option>
                                        @foreach($paralelos as $paralelo)
                                            @if($paralelo->grado_id == $matriculacion->grado_id)
                                                <option value="{{ $paralelo->id }}" {{ $matriculacion->paralelo_id == $paralelo->id ? 'selected' : '' }}>
                                                    {{ $paralelo->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fecha de Matriculación *</label>
                                    <input type="date" name="fecha_matriculacion" class="form-control" 
                                           value="{{ $matriculacion->fecha_matriculacion->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal de Creación -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ModalCreateLabel">Nueva Matriculación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.matriculaciones.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Complete los datos de la matriculación</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estudiante *</label>
                                <select name="estudiante_id" class="form-control estudiante-select-search" required>
                                    <option value="">Buscar estudiante por nombre o CI...</option>
                                    @foreach($estudiantes as $estudiante)
                                        @php
                                            $edad = \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age;
                                            $recomendacion = '';
                                            if ($edad >= 3 && $edad <= 5) {
                                                $recomendacion = 'KINDER';
                                            } elseif ($edad >= 6 && $edad <= 11) {
                                                $recomendacion = 'PRIMARIA';
                                            } elseif ($edad >= 12 && $edad <= 14) {
                                                $recomendacion = 'SECUNDARIA';
                                            }
                                            $nombreCompleto = $estudiante->nombre . ' ' . $estudiante->paterno . ' ' . $estudiante->materno;
                                        @endphp
                                        <option value="{{ $estudiante->id }}" 
                                                data-edad="{{ $edad }}"
                                                data-ci="{{ $estudiante->ci }}"
                                                data-telefono="{{ $estudiante->telefono }}"
                                                data-genero="{{ ucfirst($estudiante->genero) }}"
                                                data-recomendacion="{{ $recomendacion }}"
                                                {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}
                                                data-nombre="{{ $nombreCompleto }}">
                                            {{ $nombreCompleto }} - CI: {{ $estudiante->ci }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted mt-2">
                                    Busque por nombre, apellido o CI. Se mostrará la edad y recomendación de grado para cada estudiante.
                                </small>
                                @error('estudiante_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <!-- Info del estudiante -->
                                <div id="info-estudiante" class="mt-3" style="display: none;">
                                    <!-- El contenido se generará dinámicamente via JavaScript -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado *</label>
                                <select name="estado" class="form-control" required>
                                    <option value="activo" {{ old('estado') == 'activo' || old('estado') == null ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gestión *</label>
                                <select name="gestion_id" class="form-control gestion-select" required>
                                    <option value="">Seleccione una gestión</option>
                                    @foreach($gestiones as $gestion)
                                        <option value="{{ $gestion->id }}" {{ old('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                            {{ $gestion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gestion_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Turno *</label>
                                <select name="turno_id" class="form-control" required>
                                    <option value="">Seleccione un turno</option>
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->id }}" {{ old('turno_id') == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('turno_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nivel *</label>
                                <select name="nivel_id" class="form-control nivel-select" required>
                                    <option value="">Seleccione un nivel</option>
                                    @foreach($niveles as $nivel)
                                        <option value="{{ $nivel->id }}" {{ old('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nivel_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Grado *</label>
                                <select name="grado_id" class="form-control grado-select" required>
                                    <option value="">Seleccione un grado</option>
                                </select>
                                @error('grado_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Paralelo *</label>
                                <select name="paralelo_id" class="form-control paralelo-select" required>
                                    <option value="">Seleccione un paralelo</option>
                                </select>
                                @error('paralelo_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Fecha de Matriculación *</label>
                                <input type="date" name="fecha_matriculacion" class="form-control" 
                                       value="{{ old('fecha_matriculacion', date('Y-m-d')) }}" required>
                                @error('fecha_matriculacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
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
        .card-outline.card-warning {
            border-top-color: #ffc107;
        }
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .card-outline.card-success {
            border-top-color: #28a745;
        }
        .table-responsive {
            overflow-x: auto;
        }
        #matriculacionesTable_wrapper .row {
            margin: 0;
        }
        .badge {
            font-size: 0.75em;
        }
        .btn-warning {
            color: #212529 !important;
        }
        /* Estilos mejorados para Select2 */
        .select2-result-student-custom {
            padding: 12px !important;
            border-bottom: 1px solid #e9ecef;
            min-height: auto !important;
        }
        .select2-result-student-custom:last-child {
            border-bottom: none;
        }
        
        .student-name-display {
            font-weight: bold !important;
            color: #333 !important;
            font-size: 1.1em !important;
            margin-bottom: 8px !important;
            border-bottom: 2px solid #e9ecef !important;
            padding-bottom: 5px !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            display: block !important;
            width: 100% !important;
        }
        
        .student-info-grid-custom {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 15px !important;
            font-size: 0.9em !important;
            color: #666 !important;
            margin-bottom: 10px !important;
            width: 100% !important;
        }
        
        .info-item {
            display: flex !important;
            align-items: center !important;
            min-width: 120px !important;
            white-space: nowrap !important;
            flex: 1 1 auto !important;
        }
        
        .info-icon {
            margin-right: 8px !important;
            color: #007bff !important;
            flex-shrink: 0 !important;
            width: 16px !important;
        }
        
        .student-recommendation {
            margin-top: 8px !important;
            width: 100% !important;
        }
        
        /* Forzar layout horizontal */
        .select2-container--bootstrap4 .select2-results__option {
            padding: 0 !important;
            margin: 2px !important;
            display: block !important;
            width: 100% !important;
        }
        
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }
        
        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #e9ecef !important;
        }
        
        /* Prevenir texto vertical */
        .select2-results__option * {
            display: inline !important;
            writing-mode: initial !important;
            text-orientation: initial !important;
        }
        
        .student-name-display,
        .student-info-grid-custom,
        .info-item,
        .student-recommendation {
            writing-mode: horizontal-tb !important;
            text-orientation: mixed !important;
            direction: ltr !important;
        }
        .student-info-highlight {
            margin-top: 8px;
        }
        .edad-badge {
            background-color: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            margin-left: 5px;
        }
        .select2-container--bootstrap4 .select2-results__option {
            padding: 0;
            margin: 2px;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }
        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #e9ecef !important;
        }
        .alert {
            margin-bottom: 0 !important;
        }
        .alert-info {
            background-color: #e3f2fd;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #757575;
            line-height: calc(1.5em + 0.75rem);
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 50%;
            right: 3px;
            width: 20px;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
            top: 60%;
            border-color: #343a40 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            width: 0;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Agregar este script al final -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    @if($errors->any())
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        });
    @endif

    $(document).ready(function() {
        // Definir variables globales para los selects
        var estudianteSelect = $('.estudiante-select-search');
        
        // Inicializar Select2 para la búsqueda de estudiantes
        $('.estudiante-select-search').select2({
            theme: 'bootstrap4',
            placeholder: 'Buscar estudiante por nombre o CI...',
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                }
            },
            matcher: function(params, data) {
                if (!params.term) {
                    return data;
                }

                if (data.id === '') {
                    return null;
                }

                var searchText = params.term.toLowerCase();
                var dataText = data.text.toLowerCase();
                var $element = $(data.element);
                var ci = $element.data('ci') ? $element.data('ci').toString().toLowerCase() : '';
                var nombre = $element.data('nombre') ? $element.data('nombre').toLowerCase() : '';

                if (dataText.indexOf(searchText) > -1 || 
                    ci.indexOf(searchText) > -1 || 
                    nombre.indexOf(searchText) > -1) {
                    return data;
                }

                return null;
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                if (data.loading) return data.text;
                if (!data.element) return data.text;

                var $element = $(data.element);
                var edad = $element.data('edad') || 'N/A';
                var ci = $element.data('ci') || 'N/A';
                var telefono = $element.data('telefono') || 'N/A';
                var genero = $element.data('genero') || 'N/A';
                var recomendacion = $element.data('recomendacion') || '';
                
                // Usar el texto del option directamente y limpiarlo
                var nombreDisplay = data.text;
                if (nombreDisplay && nombreDisplay.indexOf(' - CI:') > -1) {
                    nombreDisplay = nombreDisplay.split(' - CI:')[0].trim();
                }
                
                // Crear el HTML de manera más robusta
                var $container = $('<div class="select2-result-student-custom"></div>');
                
                // Nombre del estudiante
                var $nombre = $('<div class="student-name-display"></div>').text(nombreDisplay);
                $container.append($nombre);
                
                // Grid de información
                var $infoGrid = $('<div class="student-info-grid-custom"></div>');
                
                // CI
                var $ciDiv = $('<div class="info-item"></div>');
                $ciDiv.append($('<i class="fas fa-id-card info-icon"></i>'));
                $ciDiv.append($('<span></span>').text('CI: ' + ci));
                $infoGrid.append($ciDiv);
                
                // Edad
                var $edadDiv = $('<div class="info-item"></div>');
                $edadDiv.append($('<i class="fas fa-calendar-alt info-icon"></i>'));
                $edadDiv.append($('<span></span>').text('Edad: ' + edad + ' años'));
                $infoGrid.append($edadDiv);
                
                // Género
                var $generoDiv = $('<div class="info-item"></div>');
                var iconoGenero = (genero && genero.toLowerCase() === 'masculino') ? 'male' : 'female';
                $generoDiv.append($('<i class="fas fa-' + iconoGenero + ' info-icon"></i>'));
                $generoDiv.append($('<span></span>').text(genero));
                $infoGrid.append($generoDiv);
                
                $container.append($infoGrid);
                
                // Recomendación
                if (recomendacion && recomendacion.trim() !== '') {
                    var $recomendacionDiv = $('<div class="student-recommendation"></div>');
                    var $alertDiv = $('<div class="alert alert-info p-2 mb-0"></div>');
                    $alertDiv.append($('<i class="fas fa-graduation-cap"></i>'));
                    $alertDiv.append($('<strong></strong>').text(' Recomendación: '));
                    $alertDiv.append($('<span></span>').text(recomendacion));
                    $recomendacionDiv.append($alertDiv);
                    $container.append($recomendacionDiv);
                }
                
                return $container[0].outerHTML;
            }
        });

        // Reinicializar Select2 cuando se abra el modal
        $('#ModalCreate').on('shown.bs.modal', function () {
            $('.estudiante-select-search').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#ModalCreate')
            });
        });

        // Manejar el cambio de estudiante
        $('.estudiante-select-search').on('select2:select', function(e) {
            var data = e.params.data;
            var $option = $(data.element);
            
            if ($option.length) {
                var edad = $option.data('edad') || 'No disponible';
                var ci = $option.data('ci') || 'No disponible';
                var telefono = $option.data('telefono') || 'No disponible';
                var genero = $option.data('genero') || 'No especificado';
                var recomendacion = $option.data('recomendacion');
                var nombre = $option.data('nombre');

                // Actualizar la información visible
                $('#info-estudiante').fadeIn();
                
                // Información básica
                var infoHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-user-graduate text-primary"></i> 
                                ${nombre}
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><i class="fas fa-birthday-cake text-info"></i> <strong>Edad:</strong> ${edad} años</p>
                                    <p><i class="fas fa-id-card text-success"></i> <strong>CI:</strong> ${ci}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="fas fa-venus-mars text-warning"></i> <strong>Género:</strong> ${genero || 'No especificado'}</p>
                                    <p><i class="fas fa-phone text-danger"></i> <strong>Teléfono:</strong> ${telefono || 'No registrado'}</p>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                $('#info-estudiante').html(infoHtml);

                // Mostrar recomendación si existe
                if (recomendacion) {
                    var recomendacionHtml = `
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-graduation-cap"></i>
                            <strong>Nivel Recomendado:</strong> ${recomendacion}
                        </div>`;
                    $('#info-estudiante').append(recomendacionHtml);
                    
                    // Preseleccionar el nivel recomendado
                    $('.nivel-select option').each(function() {
                        if ($(this).text().trim().toUpperCase().includes(recomendacion.trim().toUpperCase())) {
                            $('.nivel-select').val($(this).val()).trigger('change');
                        }
                    });
                }
            } else {
                $('#info-estudiante').hide();
            }
        });

        // Eventos para verificar disponibilidad
        $('.gestion-select').on('change', function() {
            var gestionId = $(this).val();
            var estudianteId = $(this).closest('.modal').find('select[name="estudiante_id"]').val();
            if (estudianteId) {
                verificarDisponibilidad(gestionId, estudianteId);
            }
        });

        $('select[name="estudiante_id"]').on('change', function() {
            var estudianteId = $(this).val();
            var gestionId = $(this).closest('.modal').find('.gestion-select').val();
            if (gestionId) {
                verificarDisponibilidad(gestionId, estudianteId);
            }
        });

        // Verificar si hay datos para la tabla
        var hasData = {{ count($matriculaciones) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#matriculacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "dom": 'Bfrtip',
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 } // Desactiva ordenamiento en la última columna (Acciones)
                ],
                "buttons": [
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excluye la última columna (Acciones)
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
                        title: 'Listado de Matriculaciones',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Matriculaciones',
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
                        title: 'Listado de Matriculaciones',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            });

            // Ocultar controles nativos de DataTables
            $('.dataTables_length, .dataTables_filter').hide();

            // Eventos para controles personalizados
            $('#mostrar').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $('#buscar').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Botones de exportación
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
            // Si no hay datos, deshabilitar los controles de exportación
            $('#copiar, #pdf, #csv, #excel, #imprimir').prop('disabled', true).addClass('disabled');
            
            // Deshabilitar también los controles de búsqueda y mostrar
            $('#buscar, #mostrar').prop('disabled', true);
            
            console.log('No hay datos para mostrar en la tabla');
        }

        // Funcionalidad para selección cascada (Nivel -> Grado -> Paralelo)
        $('.nivel-select').on('change', function() {
            var nivelId = $(this).val();
            var form = $(this).closest('form');
            var gradoSelect = form.find('.grado-select');
            var paraleloSelect = form.find('.paralelo-select');
            
            // Limpiar selects dependientes
            gradoSelect.html('<option value="">Seleccione un grado</option>');
            paraleloSelect.html('<option value="">Seleccione un paralelo</option>');
            
            if (nivelId) {
                $.ajax({
                    url: '{{ route("admin.matriculaciones.grados-by-nivel") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { nivel_id: nivelId },
                    success: function(response) {
                        console.log('Respuesta de grados:', response);
                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(grado) {
                                gradoSelect.append(new Option(grado.nombre, grado.id));
                            });
                            console.log('Grados cargados exitosamente');
                        } else {
                            console.log('No se encontraron grados');
                            Swal.fire({
                                icon: 'info',
                                title: 'Sin grados',
                                text: response.message || 'No se encontraron grados para el nivel seleccionado'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar grados:', error);
                        gradoSelect.html('<option value="">Error al cargar grados</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los grados. Por favor, intente nuevamente.'
                        });
                    }
                });
            }
        });

        $('.grado-select').on('change', function() {
            var gradoId = $(this).val();
            var form = $(this).closest('form');
            var paraleloSelect = form.find('.paralelo-select');
            
            // Limpiar select dependiente
            paraleloSelect.html('<option value="">Seleccione un paralelo</option>');
            
            if (gradoId) {
                $.ajax({
                    url: '{{ route("admin.matriculaciones.paralelos-by-grado") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { grado_id: gradoId },
                    success: function(response) {
                        console.log('Respuesta de paralelos:', response);
                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(paralelo) {
                                paraleloSelect.append(new Option(paralelo.nombre, paralelo.id));
                            });
                            console.log('Paralelos cargados exitosamente');
                        } else {
                            console.log('No se encontraron paralelos');
                            Swal.fire({
                                icon: 'info',
                                title: 'Sin paralelos',
                                text: response.message || 'No se encontraron paralelos para el grado seleccionado'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar paralelos:', error);
                        paraleloSelect.html('<option value="">Error al cargar paralelos</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los paralelos. Por favor, intente nuevamente.'
                        });
                    }
                });
            }
        });

        // Verificar disponibilidad cuando cambia la gestión o el estudiante
        function verificarDisponibilidad(gestionId, estudianteId) {
            if (!gestionId || !estudianteId) return;

            $.ajax({
                url: '{{ route("admin.matriculaciones.estudiantes-disponibles") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    gestion_id: gestionId,
                    estudiante_id: estudianteId
                },
                    success: function(response) {
                        if (response.error) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Estudiante ya matriculado',
                                text: response.error,
                                confirmButtonText: 'Entendido'
                            });
                            $('.estudiante-select-search').val('').trigger('change');
                            return;
                        }

                        // Mantener el estudiante seleccionado
                        var selectedEstudianteId = $('.estudiante-select-search').val();
                        
                        // Verificar si el estudiante ya está matriculado
                        if (selectedEstudianteId) {
                            var isMatriculado = !response.some(function(estudiante) {
                                return estudiante.id == selectedEstudianteId;
                            });
                            
                            if (isMatriculado) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Estudiante ya matriculado',
                                    text: 'El estudiante seleccionado ya está matriculado en esta gestión.',
                                    confirmButtonText: 'Entendido'
                                });
                                $('.estudiante-select-search').val('').trigger('change');
                            }
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al verificar la disponibilidad del estudiante',
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });

    // Confirmación de eliminación con SweetAlert
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop