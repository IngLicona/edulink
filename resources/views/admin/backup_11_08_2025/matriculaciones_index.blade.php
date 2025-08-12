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

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Nueva Matriculación
                </button>
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

                <!-- Botones de exportación -->
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

                                        <!-- Botón Ver -->
                                        <button type="button" class="btn btn-info btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $matriculacion->id }}" 
                                                title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalUpdate{{ $matriculacion->id }}" 
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('admin.matriculaciones.destroy', $matriculacion->id) }}" 
                                              method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

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

<!-- Modal Crear -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ModalCreateLabel">Nueva Matriculación</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
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
                                <select name="estudiante_id" class="form-control" required>
                                    <option value="">Seleccione un estudiante</option>
                                    @foreach($estudiantes as $estudiante)
                                        <option value="{{ $estudiante->id }}" {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                            {{ $estudiante->nombre_completo }} - CI: {{ $estudiante->ci }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estudiante_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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

<script>
    @if ($errors->any())
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        });
    @endif

    $(document).ready(function() {
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
            var gradoSelect = $(this).closest('.modal').find('.grado-select');
            var paraleloSelect = $(this).closest('.modal').find('.paralelo-select');
            
            // Limpiar selects dependientes
            gradoSelect.html('<option value="">Seleccione un grado</option>');
            paraleloSelect.html('<option value="">Seleccione un paralelo</option>');
            
            if (nivelId) {
                $.ajax({
                    url: '{{ route("admin.matriculaciones.grados-by-nivel") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nivel_id: nivelId
                    },
                    success: function(response) {
                        $.each(response, function(key, grado) {
                            gradoSelect.append('<option value="' + grado.id + '">' + grado.nombre + '</option>');
                        });
                    }
                });
            }
        });

        $('.grado-select').on('change', function() {
            var gradoId = $(this).val();
            var paraleloSelect = $(this).closest('.modal').find('.paralelo-select');
            
            // Limpiar select dependiente
            paraleloSelect.html('<option value="">Seleccione un paralelo</option>');
            
            if (gradoId) {
                $.ajax({
                    url: '{{ route("admin.matriculaciones.paralelos-by-grado") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        grado_id: gradoId
                    },
                    success: function(response) {
                        $.each(response, function(key, paralelo) {
                            paraleloSelect.append('<option value="' + paralelo.id + '">' + paralelo.nombre + '</option>');
                        });
                    }
                });
            }
        });

        // Filtrar estudiantes disponibles por gestión
        $('.gestion-select').on('change', function() {
            var gestionId = $(this).val();
            var estudianteSelect = $(this).closest('.modal').find('select[name="estudiante_id"]');
            
            if (gestionId && $(this).closest('#ModalCreate').length > 0) {
                $.ajax({
                    url: '{{ route("admin.matriculaciones.estudiantes-disponibles") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        gestion_id: gestionId
                    },
                    success: function(response) {
                        estudianteSelect.html('<option value="">Seleccione un estudiante</option>');
                        $.each(response, function(key, estudiante) {
                            estudianteSelect.append('<option value="' + estudiante.id + '">' + estudiante.text + '</option>');
                        });
                    }
                });
            }
        });
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