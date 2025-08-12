@extends('adminlte::page')

@section('content_header')
    <h1>Gestión de Pagos</h1>
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
                    <span class="ml-2">Pagos</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                <div class="d-flex align-items-center">
                    @can('admin.pagos.reportes')
                        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#ModalReportes">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </button>
                    @endcan

                    @can('admin.pagos.store')
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                            <i class="fas fa-plus"></i> Registrar Nuevo Pago
                        </button>
                    @endcan
                </div>
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

                <!-- Tarjetas de estadísticas -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $pagos->count() }}</h3>
                                <p>Total Pagos</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $pagos->where('estado', 'completado')->count() }}</h3>
                                <p>Pagos Completados</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $pagos->where('estado', 'pendiente')->count() }}</h3>
                                <p>Pagos Pendientes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ number_format($pagos->where('estado', 'completado')->sum('monto'), 2) }} {{ $configuracion->divisa }}</h3>
                                <p>Total Recaudado</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>

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
                    <table id="pagosTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estudiante</th>
                                <th>CI</th>
                                <th>Gestión</th>
                                <th>Grado</th>
                                <th>Monto</th>
                                <th>Método Pago</th>
                                <th>Fecha Pago</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pagos as $index => $pago)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pago->matriculacion->estudiante->nombre }} {{ $pago->matriculacion->estudiante->paterno }} {{ $pago->matriculacion->estudiante->materno }}</td>
                                    <td>{{ $pago->matriculacion->estudiante->ci }}</td>
                                    <td><span class="badge badge-primary">{{ $pago->matriculacion->gestion->nombre }}</span></td>
                                    <td>{{ $pago->matriculacion->grado->nombre }} {{ $pago->matriculacion->paralelo->nombre }}</td>
                                    <td class="text-right"><strong>{{ number_format($pago->monto, 2) }} {{ $configuracion->divisa }}</strong></td>
                                    <td>{{ $pago->metodo_pago_formateado }}</td>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($pago->estado)
                                            @case('completado')
                                                <span class="badge badge-success">COMPLETADO</span>
                                                @break
                                            @case('pendiente')
                                                <span class="badge badge-warning">PENDIENTE</span>
                                                @break
                                            @case('cancelado')
                                                <span class="badge badge-secondary">CANCELADO</span>
                                                @break
                                            @case('anulado')
                                                <span class="badge badge-danger">ANULADO</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ strtoupper($pago->estado) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @can('admin.pagos.comprobante')
                                            <a href="{{ route('admin.pagos.comprobante', $pago->id) }}" 
                                               class="btn btn-info btn-sm mr-1" 
                                               title="Descargar Comprobante" target="_blank">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        @endcan

                                        <button type="button" class="btn btn-warning btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $pago->id }}" 
                                                title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @can('admin.pagos.store')
                                            <button type="button" class="btn btn-success btn-sm mr-1" 
                                                    data-toggle="modal" data-target="#ModalUpdate{{ $pago->id }}" 
                                                    title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endcan

                                        @can('admin.pagos.destroy')
                                            <form action="{{ route('admin.pagos.destroy', $pago->id) }}" 
                                                  method="POST" class="form-eliminar d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>

                                <!-- Modal Ver Detalles -->
                                <div class="modal fade" id="ModalVer{{ $pago->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $pago->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="ModalVerLabel{{ $pago->id }}">Detalles del Pago #{{ $pago->id }}</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Información del Estudiante</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Nombre:</strong> {{ $pago->matriculacion->estudiante->nombre }} {{ $pago->matriculacion->estudiante->paterno }} {{ $pago->matriculacion->estudiante->materno }}</p>
                                                                <p><strong>CI:</strong> {{ $pago->matriculacion->estudiante->ci }}</p>
                                                                <p><strong>Gestión:</strong> <span class="badge badge-primary">{{ $pago->matriculacion->gestion->nombre }}</span></p>
                                                                <p><strong>Curso:</strong> {{ $pago->matriculacion->grado->nombre }} "{{ $pago->matriculacion->paralelo->nombre }}"</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-success">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-money-bill mr-2"></i>Información del Pago</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Monto:</strong> <span class="text-success h5">{{ number_format($pago->monto, 2) }} {{ $configuracion->divisa }}</span></p>
                                                                <p><strong>Método de Pago:</strong> {{ $pago->metodo_pago_formateado }}</p>
                                                                <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago->format('d/m/Y') }}</p>
                                                                <p><strong>Estado:</strong> 
                                                                    @switch($pago->estado)
                                                                        @case('completado')
                                                                            <span class="badge badge-success">COMPLETADO</span>
                                                                            @break
                                                                        @case('pendiente')
                                                                            <span class="badge badge-warning">PENDIENTE</span>
                                                                            @break
                                                                        @case('cancelado')
                                                                            <span class="badge badge-secondary">CANCELADO</span>
                                                                            @break
                                                                        @case('anulado')
                                                                            <span class="badge badge-danger">ANULADO</span>
                                                                            @break
                                                                    @endswitch
                                                                </p>
                                                                <p><strong>Descripción:</strong><br>{{ $pago->descripcion }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @can('admin.pagos.comprobante')
                                                    <a href="{{ route('admin.pagos.comprobante', $pago->id) }}" 
                                                       class="btn btn-info" target="_blank">
                                                        <i class="fas fa-file-invoice"></i> Descargar Comprobante
                                                    </a>
                                                @endcan
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Editar -->
                                @can('admin.pagos.store')
                                    <div class="modal fade" id="ModalUpdate{{ $pago->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $pago->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="ModalUpdateLabel{{ $pago->id }}">Editar Pago #{{ $pago->id }}</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('admin.pagos.update', $pago->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Matriculación *</label>
                                                                    <select name="matriculacion_id" class="form-control" required>
                                                                        @foreach($matriculaciones as $matriculacion)
                                                                            <option value="{{ $matriculacion->id }}" {{ $pago->matriculacion_id == $matriculacion->id ? 'selected' : '' }}>
                                                                                {{ $matriculacion->estudiante->nombre }} {{ $matriculacion->estudiante->paterno }} - {{ $matriculacion->gestion->nombre }} - {{ $matriculacion->grado->nombre }} "{{ $matriculacion->paralelo->nombre }}"
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Monto ({{ $configuracion->divisa }}) *</label>
                                                                    <input type="number" name="monto" class="form-control" 
                                                                           value="{{ $pago->monto }}" step="0.01" min="0" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Método de Pago *</label>
                                                                    <select name="metodo_pago" class="form-control" required>
                                                                        <option value="efectivo" {{ $pago->metodo_pago == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                                                        <option value="transferencia" {{ $pago->metodo_pago == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                                                        <option value="deposito" {{ $pago->metodo_pago == 'deposito' ? 'selected' : '' }}>Depósito Bancario</option>
                                                                        <option value="cheque" {{ $pago->metodo_pago == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                                                        <option value="tarjeta" {{ $pago->metodo_pago == 'tarjeta' ? 'selected' : '' }}>Tarjeta de Débito/Crédito</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Estado *</label>
                                                                    <select name="estado" class="form-control" required>
                                                                        <option value="pendiente" {{ $pago->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                                        <option value="completado" {{ $pago->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                                                                        <option value="cancelado" {{ $pago->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                                                        <option value="anulado" {{ $pago->estado == 'anulado' ? 'selected' : '' }}>Anulado</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Fecha de Pago *</label>
                                                                    <input type="date" name="fecha_pago" class="form-control" 
                                                                           value="{{ $pago->fecha_pago->format('Y-m-d') }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Descripción *</label>
                                                                    <textarea name="descripcion" class="form-control" rows="3" required>{{ $pago->descripcion }}</textarea>
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
                                @endcan
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        No hay pagos registrados.
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

<!-- Modal Crear Nuevo Pago -->
@can('admin.pagos.store')
    <div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ModalCreateLabel">Registrar Nuevo Pago</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.pagos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Complete los datos del pago</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estudiante *</label>
                                    <select name="estudiante_id" id="estudiante_select" class="form-control" required>
                                        <option value="">Seleccione un estudiante</option>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }} - CI: {{ $estudiante->ci }}
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
                                    <label>Matriculación *</label>
                                    <select name="matriculacion_id" id="matriculacion_select" class="form-control" required>
                                        <option value="">Seleccione una matriculación</option>
                                    </select>
                                    @error('matriculacion_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto ({{ $configuracion->divisa }}) *</label>
                                    <input type="number" name="monto" class="form-control" 
                                           value="{{ old('monto') }}" step="0.01" min="0" required>
                                    @error('monto')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Método de Pago *</label>
                                    <select name="metodo_pago" class="form-control" required>
                                        <option value="">Seleccione método de pago</option>
                                        <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                        <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                        <option value="deposito" {{ old('metodo_pago') == 'deposito' ? 'selected' : '' }}>Depósito Bancario</option>
                                        <option value="cheque" {{ old('metodo_pago') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta de Débito/Crédito</option>
                                    </select>
                                    @error('metodo_pago')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado *</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="completado" {{ old('estado') == 'completado' || old('estado') == null ? 'selected' : '' }}>Completado</option>
                                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="anulado" {{ old('estado') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                                    </select>
                                    @error('estado')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Pago *</label>
                                    <input type="date" name="fecha_pago" class="form-control" 
                                           value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                                    @error('fecha_pago')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <textarea name="descripcion" class="form-control" rows="3" 
                                              placeholder="Ingrese una descripción del pago..." required>{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

<!-- Modal Reportes -->
@can('admin.pagos.reportes')
    <div class="modal fade" id="ModalReportes" tabindex="-1" role="dialog" aria-labelledby="ModalReportesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="ModalReportesLabel">Generar Reportes de Pagos</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.pagos.reportes') }}" method="GET" target="_blank">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="completado">Completado</option>
                                        <option value="cancelado">Cancelado</option>
                                        <option value="anulado">Anulado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Método de Pago</label>
                                    <select name="metodo_pago" class="form-control">
                                        <option value="">Todos los métodos</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="transferencia">Transferencia Bancaria</option>
                                        <option value="deposito">Depósito Bancario</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="tarjeta">Tarjeta de Débito/Crédito</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo de Reporte</label>
                                    <select name="tipo" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">Generar Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
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
        #pagosTable_wrapper .row {
            margin: 0;
        }
        .badge {
            font-size: 0.75em;
        }
        .text-success {
            color: #28a745 !important;
        }
        .small-box {
            border-radius: 0.25rem;
        }
        .small-box .inner {
            padding: 10px;
        }
        .small-box .icon {
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 70px;
            color: rgba(0,0,0,0.15);
        }
        .small-box .icon > i {
            font-size: 70px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: all .3s linear;
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
    $(document).ready(function() {
        // Verificar si hay datos para la tabla
        var hasData = {{ count($pagos) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#pagosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "dom": 'Bfrtip',
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "order": [[0, "desc"]], // Ordenar por número descendente
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
                        title: 'Listado de Pagos',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Pagos',
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
                        title: 'Listado de Pagos',
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

        // Cargar matriculaciones cuando se selecciona un estudiante
        $('#estudiante_select').on('change', function() {
            var estudianteId = $(this).val();
            var matriculacionSelect = $('#matriculacion_select');
            
            // Limpiar select de matriculaciones
            matriculacionSelect.html('<option value="">Seleccione una matriculación</option>');
            
            if (estudianteId) {
                $.ajax({
                    url: '{{ route("admin.pagos.matriculaciones-by-estudiante") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        estudiante_id: estudianteId
                    },
                    success: function(response) {
                        $.each(response, function(key, matriculacion) {
                            matriculacionSelect.append('<option value="' + matriculacion.id + '">' + 
                                matriculacion.gestion.nombre + ' - ' + 
                                matriculacion.grado.nombre + ' "' + 
                                matriculacion.paralelo.nombre + '"</option>');
                        });
                    },
                    error: function() {
                        console.log('Error al cargar las matriculaciones');
                    }
                });
            }
        });

        // Mostrar modal si hay errores
        @if($errors->any())
            $('#ModalCreate').modal('show');
        @endif
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