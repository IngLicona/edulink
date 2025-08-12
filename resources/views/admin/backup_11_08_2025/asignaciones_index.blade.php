@extends('adminlte::page')

@section('content_header')
    <h1>Asignación de Profesores</h1>
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
                    <span class="ml-2">Asignaciones</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    <i class="fas fa-plus"></i> Nueva Asignación
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
                    <table id="asignacionesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estado</th>
                                <th>Docente</th>
                                <th>Gestión</th>
                                <th>Nivel</th>
                                <th>Grado</th>
                                <th>Paralelo</th>
                                <th>Materia</th>
                                <th>Turno</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asignaciones as $index => $asignacion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($asignacion->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($asignacion->docente && $asignacion->docente->foto)
                                                <img src="{{ asset('uploads/personal/fotos/' . $asignacion->docente->foto) }}" 
                                                     alt="Foto" width="30" height="30" class="rounded-circle mr-2">
                                            @else
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mr-2" 
                                                     style="width: 30px; height: 30px; font-size: 12px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <span>{{ $asignacion->docente_nombre_completo }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $asignacion->gestion->nombre ?? 'N/A' }}</td>
                                    <td>{{ $asignacion->nivel->nombre ?? 'N/A' }}</td>
                                    <td>{{ $asignacion->grado->nombre ?? 'N/A' }}</td>
                                    <td>{{ $asignacion->paralelo->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $asignacion->materia->nombre ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $asignacion->turno->nombre ?? 'N/A' }}</td>
                                    <td>{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="text-center">
                                        <!-- Botón Ver -->
                                        <button type="button" class="btn btn-info btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $asignacion->id }}" 
                                                title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalUpdate{{ $asignacion->id }}" 
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('admin.asignaciones.destroy', $asignacion->id) }}" 
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
                                <div class="modal fade" id="ModalVer{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $asignacion->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="ModalVerLabel{{ $asignacion->id }}">Detalles de la Asignación</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <!-- Información del Docente -->
                                                    <!-- Información del Docente -->
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Docente Asignado</h6>
                                                            </div>
                                                            <div class="card-body text-center">
                                                                @if($asignacion->docente && $asignacion->docente->foto)
                                                                    <img src="{{ asset('uploads/personal/fotos/' . $asignacion->docente->foto) }}" 
                                                                         alt="Foto" width="80" height="80" class="rounded-circle mb-2">
                                                                @else
                                                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                                         style="width: 80px; height: 80px;">
                                                                        <i class="fas fa-chalkboard-teacher fa-2x text-white"></i>
                                                                    </div>
                                                                @endif
                                                                <h6>{{ $asignacion->docente_nombre_completo }}</h6>
                                                                <p class="text-muted mb-1">{{ $asignacion->docente->profesion ?? 'Docente' }}</p>
                                                                <p class="text-muted mb-0">CI: {{ $asignacion->docente->ci ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>

                                                        <!-- Formación Académica en Modal Ver -->
                                                        <div class="card card-outline card-success mt-3">
                                                            <div class="card-header bg-success text-white">
                                                                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Formación Académica</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div id="formacion-ver-{{ $asignacion->id }}">
                                                                    <div class="text-center">
                                                                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Información de la Asignación -->
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-primary">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Detalles de Asignación</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Estado:</strong> 
                                                                    @if($asignacion->estado == 'activo')
                                                                        <span class="badge badge-success">ACTIVO</span>
                                                                    @else
                                                                        <span class="badge badge-danger">INACTIVO</span>
                                                                    @endif
                                                                </p>
                                                                <p><strong>Gestión:</strong> {{ $asignacion->gestion->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Nivel:</strong> {{ $asignacion->nivel->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Grado:</strong> {{ $asignacion->grado->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Paralelo:</strong> {{ $asignacion->paralelo->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Materia:</strong> 
                                                                    <span class="badge badge-info">{{ $asignacion->materia->nombre ?? 'N/A' }}</span>
                                                                </p>
                                                                <p><strong>Turno:</strong> {{ $asignacion->turno->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Fecha Asignación:</strong> {{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y') : 'N/A' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="ModalUpdate{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $asignacion->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="ModalUpdateLabel{{ $asignacion->id }}">Editar Asignación</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.asignaciones.update', $asignacion->id) }}" method="POST" class="form-asignacion">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="card card-outline card-primary">
                                                        <div class="card-header">
                                                            <h6 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Datos de la Asignación</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Docente *</label>
                                                                        <select name="docente_id" class="form-control docente-select" required>
                                                                            <option value="">Seleccione un docente</option>
                                                                            @foreach($docentes as $docente)
                                                                                <option value="{{ $docente->id }}" 
                                                                                    {{ $asignacion->docente_id == $docente->id ? 'selected' : '' }}
                                                                                    data-ci="{{ $docente->ci }}"
                                                                                    data-profesion="{{ $docente->profesion }}">
                                                                                    {{ $docente->paterno }} {{ $docente->materno }} {{ $docente->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Estado *</label>
                                                                        <select name="estado" class="form-control" required>
                                                                            <option value="activo" {{ $asignacion->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                                                            <option value="inactivo" {{ $asignacion->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Gestión *</label>
                                                                        <select name="gestion_id" class="form-control gestion-select" required>
                                                                            <option value="">Seleccione gestión</option>
                                                                            @foreach($gestiones as $gestion)
                                                                                <option value="{{ $gestion->id }}" {{ $asignacion->gestion_id == $gestion->id ? 'selected' : '' }}>
                                                                                    {{ $gestion->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Nivel *</label>
                                                                        <select name="nivel_id" class="form-control nivel-select" required>
                                                                            <option value="">Seleccione nivel</option>
                                                                            @foreach($niveles as $nivel)
                                                                                <option value="{{ $nivel->id }}" {{ $asignacion->nivel_id == $nivel->id ? 'selected' : '' }}>
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
                                                                            <option value="">Seleccione grado</option>
                                                                            <!-- Se carga dinámicamente -->
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Paralelo *</label>
                                                                        <select name="paralelo_id" class="form-control paralelo-select" required>
                                                                            <option value="">Seleccione paralelo</option>
                                                                            <!-- Se carga dinámicamente -->
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Materia *</label>
                                                                        <select name="materia_id" class="form-control" required>
                                                                            <option value="">Seleccione materia</option>
                                                                            @foreach($materias as $materia)
                                                                                <option value="{{ $materia->id }}" {{ $asignacion->materia_id == $materia->id ? 'selected' : '' }}>
                                                                                    {{ $materia->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Turno *</label>
                                                                        <select name="turno_id" class="form-control turno-select" required>
                                                                            <option value="">Seleccione turno</option>
                                                                            @foreach($turnos as $turno)
                                                                                <option value="{{ $turno->id }}" {{ $asignacion->turno_id == $turno->id ? 'selected' : '' }}>
                                                                                    {{ $turno->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Fecha de Asignación *</label>
                                                                        <input type="date" name="fecha_asignacion" class="form-control" 
                                                                               value="{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('Y-m-d') : '' }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="alert alert-warning alert-sm" id="conflicto-warning-{{ $asignacion->id }}" style="display: none;">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        <span class="conflicto-mensaje"></span>
                                                                    </div>
                                                                </div>
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
                                    <td colspan="11" class="text-center">
                                        No hay asignaciones registradas.
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

<!-- Modal Crear - Diseño como página completa -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%; height: 90%;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="ModalCreateLabel">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Asignaciones/Registro de una nueva asignación del docente
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.asignaciones.store') }}" method="POST" class="form-asignacion" style="height: calc(100% - 60px);">
                @csrf
                <div class="modal-body" style="height: calc(100% - 60px); overflow-y: auto;">
                    <div class="row" style="height: 100%;">
                        <!-- Panel izquierdo - Datos del docente -->
                        <div class="col-lg-6">
                            <div class="card card-primary card-outline h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-tie mr-2"></i>Datos del docente
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Búsqueda de docente -->
                                    <div class="form-group">
                                        <label for="buscar_docente">Buscar docente: (*)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-users"></i>
                                                </span>
                                            </div>
                                            <select name="docente_id" id="buscar_docente" class="form-control docente-select" required>
                                                <option value="">Seleccione un docente</option>
                                                @foreach($docentes as $docente)
                                                    <option value="{{ $docente->id }}" 
                                                        {{ old('docente_id') == $docente->id ? 'selected' : '' }}
                                                        data-ci="{{ $docente->ci }}"
                                                        data-profesion="{{ $docente->profesion }}"
                                                        data-telefono="{{ $docente->telefono }}"
                                                        data-direccion="{{ $docente->direccion }}"
                                                        data-fecha_nacimiento="{{ $docente->fecha_nacimiento }}"
                                                        data-foto="{{ $docente->foto }}">
                                                        {{ $docente->paterno }} {{ $docente->materno }} {{ $docente->nombre }} - {{ $docente->ci }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('docente_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Información del docente seleccionado -->
                                    <div id="docente-info-card" style="display: none;">
                                        <div class="card card-outline card-secondary">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center mb-3">
                                                        <div id="docente-foto-container">
                                                            <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center" 
                                                                 style="width: 120px; height: 120px;">
                                                                <i class="fas fa-user fa-3x text-white"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm">
                                                            <tr>
                                                                <td><strong>Apellidos:</strong></td>
                                                                <td id="docente-apellidos">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Nombres:</strong></td>
                                                                <td id="docente-nombres">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Carnet de identidad:</strong></td>
                                                                <td id="docente-ci">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Fecha de nacimiento:</strong></td>
                                                                <td id="docente-fecha_nacimiento">-</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm">
                                                            <tr>
                                                                <td><strong>Teléfono:</strong></td>
                                                                <td id="docente-telefono">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Dirección:</strong></td>
                                                                <td id="docente-direccion">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Correo electrónico:</strong></td>
                                                                <td id="docente-correo">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Profesión:</strong></td>
                                                                <td id="docente-profesion">-</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Formación académica -->
                                        <div class="card card-outline card-info mt-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="card-title mb-0">
                                                    <i class="fas fa-graduation-cap mr-2"></i>Formación académica
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="formacion-academica">
                                                    <p class="text-muted text-center">Seleccione un docente para ver su formación académica</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel derecho - Formulario de asignación -->
                        <div class="col-lg-6">
                            <div class="card card-info card-outline h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clipboard-list mr-2"></i>Llene los datos del formulario
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Turnos (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-clock"></i>
                                                        </span>
                                                    </div>
                                                    <select name="turno_id" class="form-control turno-select" required>
                                                        <option value="">Seleccione turno</option>
                                                        @foreach($turnos as $turno)
                                                            <option value="{{ $turno->id }}" {{ old('turno_id') == $turno->id ? 'selected' : '' }}>
                                                                {{ $turno->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('turno_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gestiones (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <select name="gestion_id" class="form-control gestion-select" required>
                                                        <option value="">Seleccione gestión</option>
                                                        @foreach($gestiones as $gestion)
                                                            <option value="{{ $gestion->id }}" {{ old('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                                                {{ $gestion->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('gestion_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Niveles (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-layer-group"></i>
                                                        </span>
                                                    </div>
                                                    <select name="nivel_id" class="form-control nivel-select" required>
                                                        <option value="">Seleccione nivel</option>
                                                        @foreach($niveles as $nivel)
                                                            <option value="{{ $nivel->id }}" {{ old('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                                                {{ $nivel->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('nivel_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Grados (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-list-alt"></i>
                                                        </span>
                                                    </div>
                                                    <select name="grado_id" class="form-control grado-select" required>
                                                        <option value="">Seleccione grado</option>
                                                        <!-- Se carga dinámicamente -->
                                                    </select>
                                                </div>
                                                @error('grado_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Paralelos (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-clone"></i>
                                                        </span>
                                                    </div>
                                                    <select name="paralelo_id" class="form-control paralelo-select" required>
                                                        <option value="">Seleccione paralelo</option>
                                                        <!-- Se carga dinámicamente -->
                                                    </select>
                                                </div>
                                                @error('paralelo_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fecha (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <input type="date" name="fecha_asignacion" class="form-control" 
                                                           value="{{ old('fecha_asignacion', date('Y-m-d')) }}" required>
                                                </div>
                                                @error('fecha_asignacion')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Materia a impartir (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-book"></i>
                                                        </span>
                                                    </div>
                                                    <select name="materia_id" class="form-control" required>
                                                        <option value="">Seleccione una materia...</option>
                                                        @foreach($materias as $materia)
                                                            <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                                                {{ $materia->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('materia_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Estado (*)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-toggle-on"></i>
                                                        </span>
                                                    </div>
                                                    <select name="estado" class="form-control" required>
                                                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : 'selected' }}>Activo</option>
                                                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
                                                </div>
                                                @error('estado')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alert de conflicto -->
                                    <div class="alert alert-warning alert-sm" id="conflicto-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span class="conflicto-mensaje"></span>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-success btn-lg mr-2">
                                            <i class="fas fa-save mr-2"></i>Guardar Asignación
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                                            <i class="fas fa-times mr-2"></i>Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .rounded-circle {
            object-fit: cover;
        }
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
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .card-outline.card-secondary {
            border-top-color: #6c757d;
        }
        .table-responsive {
            overflow-x: auto;
        }
        #asignacionesTable_wrapper .row {
            margin: 0;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .alert-sm {
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .docente-info-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.75rem;
        }
        .modal-xl {
            max-width: 95% !important;
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px);
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        .card-header.bg-primary {
            background-color: #007bff !important;
        }
        .card-header.bg-info {
            background-color: #17a2b8 !important;
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

<script>

    
    @if ($errors->any())
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        });
    @endif

    $(document).ready(function() {
        // Inicializar Select2 para mejor búsqueda de docentes
        $('#buscar_docente').select2({
            theme: 'bootstrap4',
            placeholder: 'Busque por nombre o CI...',
            allowClear: true,
            dropdownParent: $('#ModalCreate')
        });

        // Verificar si hay datos para la tabla
        var hasData = {{ count($asignaciones) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#asignacionesTable').DataTable({
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
                        title: 'Asignaciones de Profesores',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Asignaciones de Profesores',
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
                        title: 'Asignaciones de Profesores',
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
            $('#buscar, #mostrar').prop('disabled', true);
        }

        // Funciones para carga dinámica de selects
        function cargarGrados(nivelId, gradoSelect, gradoSeleccionado = null) {
            if (nivelId) {
                $.ajax({
                    url: "{{ route('admin.asignaciones.grados-by-nivel') }}",
                    type: 'POST',
                    data: {
                        nivel_id: nivelId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        gradoSelect.empty().append('<option value="">Seleccione grado</option>');
                        $.each(data, function(key, grado) {
                            var selected = gradoSeleccionado == grado.id ? 'selected' : '';
                            gradoSelect.append('<option value="' + grado.id + '" ' + selected + '>' + grado.nombre + '</option>');
                        });
                        
                        if (gradoSeleccionado) {
                            gradoSelect.val(gradoSeleccionado).trigger('change');
                        }
                    },
                    error: function() {
                        console.log('Error al cargar grados');
                    }
                });
            } else {
                gradoSelect.empty().append('<option value="">Seleccione grado</option>');
            }
        }

        function cargarParalelos(gradoId, paraleloSelect, paraleloSeleccionado = null) {
            if (gradoId) {
                $.ajax({
                    url: "{{ route('admin.asignaciones.paralelos-by-grado') }}",
                    type: 'POST',
                    data: {
                        grado_id: gradoId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        paraleloSelect.empty().append('<option value="">Seleccione paralelo</option>');
                        $.each(data, function(key, paralelo) {
                            var selected = paraleloSeleccionado == paralelo.id ? 'selected' : '';
                            paraleloSelect.append('<option value="' + paralelo.id + '" ' + selected + '>' + paralelo.nombre + '</option>');
                        });
                        
                        if (paraleloSeleccionado) {
                            paraleloSelect.val(paraleloSeleccionado);
                        }
                    },
                    error: function() {
                        console.log('Error al cargar paralelos');
                    }
                });
            } else {
                paraleloSelect.empty().append('<option value="">Seleccione paralelo</option>');
            }
        }

        // Eventos para modal de crear
        $('#ModalCreate .nivel-select').on('change', function() {
            var nivelId = $(this).val();
            var gradoSelect = $('#ModalCreate .grado-select');
            var paraleloSelect = $('#ModalCreate .paralelo-select');
            
            cargarGrados(nivelId, gradoSelect);
            paraleloSelect.empty().append('<option value="">Seleccione paralelo</option>');
        });

        $('#ModalCreate .grado-select').on('change', function() {
            var gradoId = $(this).val();
            var paraleloSelect = $('#ModalCreate .paralelo-select');
            
            cargarParalelos(gradoId, paraleloSelect);
        });

        // Reemplaza el evento change del docente en tu archivo JavaScript

        // Eventos para modales de editar
        @foreach($asignaciones as $asignacion)
            // Cargar grados y paralelos iniciales para modal de editar
            $('#ModalUpdate{{ $asignacion->id }}').on('shown.bs.modal', function() {
                var nivelId = '{{ $asignacion->nivel_id }}';
                var gradoId = '{{ $asignacion->grado_id }}';
                var paraleloId = '{{ $asignacion->paralelo_id }}';
                
                var gradoSelect = $('#ModalUpdate{{ $asignacion->id }} .grado-select');
                var paraleloSelect = $('#ModalUpdate{{ $asignacion->id }} .paralelo-select');
                
                // Cargar grados y después paralelos
                if (nivelId) {
                    cargarGrados(nivelId, gradoSelect, gradoId);
                    
                    // Esperar a que se carguen los grados antes de cargar paralelos
                    setTimeout(function() {
                        if (gradoId) {
                            cargarParalelos(gradoId, paraleloSelect, paraleloId);
                        }
                    }, 500);
                }
            });

            $('#ModalUpdate{{ $asignacion->id }} .nivel-select').on('change', function() {
                var nivelId = $(this).val();
                var gradoSelect = $('#ModalUpdate{{ $asignacion->id }} .grado-select');
                var paraleloSelect = $('#ModalUpdate{{ $asignacion->id }} .paralelo-select');
                
                if (nivelId) {
                    cargarGrados(nivelId, gradoSelect);
                } else {
                    gradoSelect.empty().append('<option value="">Seleccione grado</option>');
                }
                paraleloSelect.empty().append('<option value="">Seleccione paralelo</option>');
            });

            $('#ModalUpdate{{ $asignacion->id }} .grado-select').on('change', function() {
                var gradoId = $(this).val();
                var paraleloSelect = $('#ModalUpdate{{ $asignacion->id }} .paralelo-select');
                
                if (gradoId) {
                    cargarParalelos(gradoId, paraleloSelect);
                } else {
                    paraleloSelect.empty().append('<option value="">Seleccione paralelo</option>');
                }
            });
        @endforeach

        // Eventos para modales de Ver - Cargar formación académica
        @foreach($asignaciones as $asignacion)
            $('#ModalVer{{ $asignacion->id }}').on('shown.bs.modal', function() {
                var docenteId = '{{ $asignacion->docente_id }}';
                var formacionContainer = $('#formacion-ver-{{ $asignacion->id }}');
                
                if (docenteId) {
                    $.ajax({
                        url: "{{ route('admin.asignaciones.docente-info') }}",
                        type: 'POST',
                        data: {
                            docente_id: docenteId,
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            formacionContainer.html(
                                '<div class="text-center">' +
                                '<i class="fas fa-spinner fa-spin"></i> Cargando formación académica...' +
                                '</div>'
                            );
                        },
                        success: function(response) {
                            var formaciones = response.formaciones;
                            
                            if (formaciones && formaciones.length > 0) {
                                var formacionHtml = '<div class="table-responsive">' +
                                                   '<table class="table table-sm table-striped">' +
                                                   '<thead class="bg-light">' +
                                                   '<tr>' +
                                                   '<th><i class="fas fa-award mr-1"></i>Título</th>' +
                                                   '<th><i class="fas fa-university mr-1"></i>Institución</th>' +
                                                   '<th><i class="fas fa-layer-group mr-1"></i>Nivel</th>' +
                                                   '<th><i class="fas fa-calendar mr-1"></i>Fecha</th>' +
                                                   '</tr>' +
                                                   '</thead>' +
                                                   '<tbody>';
                                
                                formaciones.forEach(function(formacion) {
                                    var fechaFormateada = formacion.fecha_graduacion ? 
                                        new Date(formacion.fecha_graduacion).toLocaleDateString('es-ES') : 'N/A';
                                    
                                    formacionHtml += '<tr>' +
                                                    '<td><strong>' + (formacion.titulo || 'N/A') + '</strong></td>' +
                                                    '<td>' + (formacion.institucion || 'N/A') + '</td>' +
                                                    '<td><span class="badge badge-success">' + (formacion.nivel || 'N/A') + '</span></td>' +
                                                    '<td>' + fechaFormateada + '</td>' +
                                                    '</tr>';
                                });
                                
                                formacionHtml += '</tbody></table></div>';
                                
                                // Agregar estadísticas
                                var totalFormaciones = formaciones.length;
                                var nivelesUnicos = [...new Set(formaciones.map(f => f.nivel))].filter(n => n);
                                
                                formacionHtml += '<div class="mt-3 p-2 bg-light rounded">' +
                                               '<div class="row text-center">' +
                                               '<div class="col-6">' +
                                               '<h6 class="mb-1 text-primary">' + totalFormaciones + '</h6>' +
                                               '<small class="text-muted">Total Formaciones</small>' +
                                               '</div>' +
                                               '<div class="col-6">' +
                                               '<h6 class="mb-1 text-success">' + nivelesUnicos.length + '</h6>' +
                                               '<small class="text-muted">Niveles Académicos</small>' +
                                               '</div>' +
                                               '</div></div>';
                                
                                formacionContainer.html(formacionHtml);
                            } else {
                                formacionContainer.html(
                                    '<div class="alert alert-info text-center mb-0">' +
                                    '<i class="fas fa-info-circle mr-2"></i>' +
                                    '<strong>Sin formaciones registradas</strong><br>' +
                                    '<small>Este docente no tiene formaciones académicas registradas en el sistema.</small>' +
                                    '</div>'
                                );
                            }
                        },
                        error: function(xhr) {
                            console.log('Error al obtener formaciones:', xhr.responseText);
                            formacionContainer.html(
                                '<div class="alert alert-danger text-center mb-0">' +
                                '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                                '<strong>Error al cargar</strong><br>' +
                                '<small>No se pudo cargar la formación académica del docente.</small>' +
                                '</div>'
                            );
                        }
                    });
                } else {
                    formacionContainer.html(
                        '<div class="alert alert-warning text-center mb-0">' +
                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                        'No se pudo identificar al docente.' +
                        '</div>'
                    );
                }
            });
        @endforeach
// Mostrar información del docente seleccionado CON FORMACIONES
$('#buscar_docente').on('change', function() {
    var docenteId = $(this).val();
    
    if (docenteId) {
        // Hacer llamada AJAX para obtener información completa del docente
        $.ajax({
            url: "{{ route('admin.asignaciones.docente-info') }}",
            type: 'POST',
            data: {
                docente_id: docenteId,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                // Mostrar loading
                $('#docente-info-card').show();
                $('#formacion-academica').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando información...</div>');
            },
            success: function(response) {
                var docente = response.docente;
                var formaciones = response.formaciones;
                
                // Actualizar información básica del docente
                $('#docente-apellidos').text((docente.paterno || '') + ' ' + (docente.materno || ''));
                $('#docente-nombres').text(docente.nombre || 'N/A');
                $('#docente-ci').text(docente.ci || 'N/A');
                $('#docente-telefono').text(docente.telefono || 'N/A');
                $('#docente-direccion').text(docente.direccion || 'N/A');
                $('#docente-fecha_nacimiento').text(docente.fecha_nacimiento || 'N/A');
                $('#docente-profesion').text(docente.profesion || 'N/A');
                $('#docente-correo').text(docente.correo || 'N/A');
                
                // Actualizar foto
                if (docente.foto) {
                    $('#docente-foto-container').html(
                        '<img src="/uploads/personal/fotos/' + docente.foto + '" alt="Foto" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">'
                    );
                } else {
                    $('#docente-foto-container').html(
                        '<div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">' +
                        '<i class="fas fa-user fa-3x text-white"></i></div>'
                    );
                }
                
                // Actualizar formación académica
                if (formaciones && formaciones.length > 0) {
                    var formacionHtml = '<div class="table-responsive">' +
                                       '<table class="table table-sm table-striped">' +
                                       '<thead class="bg-light">' +
                                       '<tr>' +
                                       '<th>Título</th>' +
                                       '<th>Institución</th>' +
                                       '<th>Nivel</th>' +
                                       '<th>Fecha</th>' +
                                       '</tr>' +
                                       '</thead>' +
                                       '<tbody>';
                    
                    formaciones.forEach(function(formacion) {
                        var fechaFormateada = formacion.fecha_graduacion ? 
                            new Date(formacion.fecha_graduacion).toLocaleDateString('es-ES') : 'N/A';
                        
                        formacionHtml += '<tr>' +
                                        '<td><strong>' + (formacion.titulo || 'N/A') + '</strong></td>' +
                                        '<td>' + (formacion.institucion || 'N/A') + '</td>' +
                                        '<td><span class="badge badge-info">' + (formacion.nivel || 'N/A') + '</span></td>' +
                                        '<td>' + fechaFormateada + '</td>' +
                                        '</tr>';
                    });
                    
                    formacionHtml += '</tbody></table></div>';
                    $('#formacion-academica').html(formacionHtml);
                } else {
                    $('#formacion-academica').html(
                        '<div class="alert alert-info text-center mb-0">' +
                        '<i class="fas fa-info-circle mr-2"></i>' +
                        'No se registraron formaciones académicas para este docente.' +
                        '</div>'
                    );
                }
                
                $('#docente-info-card').show();
            },
            error: function(xhr) {
                console.log('Error al obtener información del docente:', xhr.responseText);
                $('#formacion-academica').html(
                    '<div class="alert alert-danger text-center mb-0">' +
                    '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                    'Error al cargar la información del docente.' +
                    '</div>'
                );
                // Aún mostrar la información básica disponible
                var selectedOption = $('#buscar_docente').find('option:selected');
                if (selectedOption.val()) {
                    var apellidos = selectedOption.text().split(' - ')[0].split(' ').slice(0, 2).join(' ');
                    var nombres = selectedOption.text().split(' - ')[0].split(' ').slice(2).join(' ');
                    var ci = selectedOption.data('ci');
                    var profesion = selectedOption.data('profesion');
                    
                    $('#docente-apellidos').text(apellidos || 'N/A');
                    $('#docente-nombres').text(nombres || 'N/A');
                    $('#docente-ci').text(ci || 'N/A');
                    $('#docente-profesion').text(profesion || 'N/A');
                    
                    $('#docente-info-card').show();
                }
            }
        });
    } else {
        $('#docente-info-card').hide();
    }
});

        // Verificar conflictos en tiempo real
        $('.form-asignacion').on('change', 'select[name="docente_id"], select[name="gestion_id"], select[name="turno_id"]', function() {
            verificarConflicto($(this).closest('form'));
        });

        // Validación antes del envío del formulario
        $('.form-asignacion').on('submit', function(e) {
            var form = $(this);
            var warningDiv = form.find('[id^="conflicto-warning"]:visible');
            
            if (warningDiv.length > 0) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Continuar con conflicto?',
                    text: "Existe un conflicto de horario. ¿Desea continuar de todas formas?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
                return false;
            }
        });

        // Limpiar formulario al cerrar modal
        $('#ModalCreate').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#buscar_docente').val(null).trigger('change');
            $('#docente-info-card').hide();
            $('#conflicto-warning').hide();
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