@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Pagos del Estudiante</h1>
        <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Información del estudiante -->
        <div class="card card-outline card-info mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Información del Estudiante</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Nombre Completo:</strong><br>
                        {{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}
                    </div>
                    <div class="col-md-2">
                        <strong>CI:</strong><br>
                        {{ $estudiante->ci }}
                    </div>
                    <div class="col-md-2">
                        <strong>Teléfono:</strong><br>
                        {{ $estudiante->telefono }}
                    </div>
                    <div class="col-md-3">
                        <strong>Dirección:</strong><br>
                        {{ $estudiante->direccion }}
                    </div>
                    <div class="col-md-2">
                        <strong>Estado:</strong><br>
                        <span class="badge badge-{{ $estudiante->estado == 'activo' ? 'success' : 'secondary' }}">
                            {{ strtoupper($estudiante->estado) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de pagos -->
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
                        <h3>{{ number_format($pagos->where('estado', 'completado')->sum('monto'), 2) }} Bs.</h3>
                        <p>Total Pagado</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de pagos -->
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historial de Pagos</h5>
                @can('admin.pagos.store')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                        <i class="fas fa-plus"></i> Nuevo Pago
                    </button>
                @endcan
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
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Gestión</th>
                                <th>Grado</th>
                                <th>Monto</th>
                                <th>Método Pago</th>
                                <th>Fecha Pago</th>
                                <th>Estado</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pagos as $index => $pago)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge badge-primary">{{ $pago->matriculacion->gestion->nombre }}</span></td>
                                    <td>{{ $pago->matriculacion->grado->nombre }} {{ $pago->matriculacion->paralelo->nombre }}</td>
                                    <td class="text-right"><strong>{{ number_format($pago->monto, 2) }} Bs.</strong></td>
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
                                    <td>{{ Str::limit($pago->descripcion, 30) }}</td>
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
                                                                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Información Académica</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Gestión:</strong> <span class="badge badge-primary">{{ $pago->matriculacion->gestion->nombre }}</span></p>
                                                                <p><strong>Curso:</strong> {{ $pago->matriculacion->grado->nombre }} "{{ $pago->matriculacion->paralelo->nombre }}"</p>
                                                                <p><strong>Fecha de Matriculación:</strong> {{ $pago->matriculacion->created_at->format('d/m/Y') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-success">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-money-bill mr-2"></i>Información del Pago</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Monto:</strong> <span class="text-success h5">{{ number_format($pago->monto, 2) }} Bs.</span></p>
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
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No hay pagos registrados para este estudiante.
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
                            <strong>Registrando pago para:</strong> {{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Matriculación *</label>
                                    <select name="matriculacion_id" class="form-control" required>
                                        <option value="">Seleccione una matriculación</option>
                                        @foreach($estudiante->matriculaciones as $matriculacion)
                                            <option value="{{ $matriculacion->id }}">
                                                {{ $matriculacion->gestion->nombre }} - {{ $matriculacion->grado->nombre }} "{{ $matriculacion->paralelo->nombre }}"
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto (Bs.) *</label>
                                    <input type="number" name="monto" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Método de Pago *</label>
                                    <select name="metodo_pago" class="form-control" required>
                                        <option value="">Seleccione método de pago</option>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado *</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="completado" selected>Completado</option>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="cancelado">Cancelado</option>
                                        <option value="anulado">Anulado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Pago *</label>
                                    <input type="date" name="fecha_pago" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <textarea name="descripcion" class="form-control" rows="3" 
                                              placeholder="Ingrese una descripción del pago..." required></textarea>
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
@stop

@section('css')
    <style>
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
        .card-outline {
            border-top: 3px solid;
        }
        .card-outline.card-info {
            border-top-color: #17a2b8;
        }
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .badge {
            font-size: 0.75em;
        }
        .text-success {
            color: #28a745 !important;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
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
    });
</script>
@stop