@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Padres de Familia</h1>
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
                    <span class="ml-2">Padres de Familia</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Registrar nuevo PPFF
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
                    <table id="ppffsTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Nombres y Apellidos</th>
                                <th>CI</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Teléfono</th>
                                <th>Parentesco</th>
                                <th>Ocupación</th>
                                <th>Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ppffs as $index => $ppff)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ppff->nombre_completo }}</td>
                                    <td><strong>{{ $ppff->ci }}</strong></td>
                                    <td>{{ $ppff->fecha_nacimiento ? $ppff->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</td>
                                    <td>{{ $ppff->telefono }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($ppff->parentesco) }}</span>
                                    </td>
                                    <td>{{ $ppff->ocupacion ?? 'No especificada' }}</td>
                                    <td class="text-center">
                                        @if($ppff->estudiantes_count > 0)
                                            <span class="badge badge-success">{{ $ppff->estudiantes->count() }}</span>
                                        @else
                                            <span class="badge badge-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Botón Ver -->
                                        <button type="button" class="btn btn-info btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $ppff->id }}" 
                                                title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalUpdate{{ $ppff->id }}" 
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('admin.ppff.destroy', $ppff->id) }}" 
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
                                <div class="modal fade" id="ModalVer{{ $ppff->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $ppff->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="ModalVerLabel{{ $ppff->id }}">Información del PPFF</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Datos Personales</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Nombres:</strong> {{ $ppff->nombre }}</p>
                                                                <p><strong>Apellidos:</strong> {{ $ppff->paterno }} {{ $ppff->materno }}</p>
                                                                <p><strong>CI:</strong> {{ $ppff->ci }}</p>
                                                                <p><strong>Fecha de Nacimiento:</strong> {{ $ppff->fecha_nacimiento ? $ppff->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                                                                <p><strong>Edad:</strong> 
                                                                    @if($ppff->fecha_nacimiento)
                                                                        {{ $ppff->edad }} años
                                                                    @else
                                                                        No calculable
                                                                    @endif
                                                                </p>
                                                                <p><strong>Teléfono:</strong> {{ $ppff->telefono }}</p>
                                                                <p><strong>Parentesco:</strong> <span class="badge badge-info">{{ ucfirst($ppff->parentesco) }}</span></p>
                                                                <p><strong>Ocupación:</strong> {{ $ppff->ocupacion ?? 'No especificada' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-success">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Estudiantes a cargo</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @if($ppff->estudiantes->count() > 0)
                                                                    @foreach($ppff->estudiantes as $estudiante)
                                                                        <div class="mb-2 p-2 border rounded">
                                                                            <p class="mb-1"><strong>{{ $estudiante->nombre_completo }}</strong></p>
                                                                            <p class="mb-0 text-muted"><small>CI: {{ $estudiante->ci }}</small></p>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">No tiene estudiantes asociados</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($ppff->direccion)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-outline card-warning">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Dirección</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="mb-0">{{ $ppff->direccion }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="ModalUpdate{{ $ppff->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $ppff->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="ModalUpdateLabel{{ $ppff->id }}">Editar PPFF</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.ppff.update', $ppff->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Nombres *</label>
                                                                <input type="text" name="nombre" class="form-control" value="{{ $ppff->nombre }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Apellido Paterno *</label>
                                                                <input type="text" name="paterno" class="form-control" value="{{ $ppff->paterno }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Apellido Materno</label>
                                                                <input type="text" name="materno" class="form-control" value="{{ $ppff->materno }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>CI (Cédula de Identidad) *</label>
                                                                <input type="text" name="ci" class="form-control" value="{{ $ppff->ci }}" required placeholder="Ingrese CI">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Fecha de Nacimiento</label>
                                                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $ppff->fecha_nacimiento ? $ppff->fecha_nacimiento->format('Y-m-d') : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Teléfono *</label>
                                                                <input type="text" name="telefono" class="form-control" value="{{ $ppff->telefono }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Parentesco *</label>
                                                                <select name="parentesco" class="form-control" required>
                                                                    <option value="">Seleccione parentesco</option>
                                                                    <option value="padre" {{ $ppff->parentesco == 'padre' ? 'selected' : '' }}>Padre</option>
                                                                    <option value="madre" {{ $ppff->parentesco == 'madre' ? 'selected' : '' }}>Madre</option>
                                                                    <option value="tutor" {{ $ppff->parentesco == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                                                    <option value="abuelo" {{ $ppff->parentesco == 'abuelo' ? 'selected' : '' }}>Abuelo</option>
                                                                    <option value="abuela" {{ $ppff->parentesco == 'abuela' ? 'selected' : '' }}>Abuela</option>
                                                                    <option value="tio" {{ $ppff->parentesco == 'tio' ? 'selected' : '' }}>Tío</option>
                                                                    <option value="tia" {{ $ppff->parentesco == 'tia' ? 'selected' : '' }}>Tía</option>
                                                                    <option value="hermano" {{ $ppff->parentesco == 'hermano' ? 'selected' : '' }}>Hermano</option>
                                                                    <option value="hermana" {{ $ppff->parentesco == 'hermana' ? 'selected' : '' }}>Hermana</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Ocupación</label>
                                                                <input type="text" name="ocupacion" class="form-control" value="{{ $ppff->ocupacion }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Dirección</label>
                                                                <textarea name="direccion" class="form-control" rows="3">{{ $ppff->direccion }}</textarea>
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
                                    <td colspan="9" class="text-center">
                                        No hay padres de familia registrados.
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
                <h5 class="modal-title" id="ModalCreateLabel">Registro de nuevo PPFF</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.ppff.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Llene los datos del formulario</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombres *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ingrese nombres" required>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Paterno *</label>
                                <input type="text" name="paterno" class="form-control" value="{{ old('paterno') }}" placeholder="Apellido paterno" required>
                                @error('paterno')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" name="materno" class="form-control" value="{{ old('materno') }}" placeholder="Apellido materno">
                                @error('materno')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CI (Cédula de Identidad) *</label>
                                <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" placeholder="Ingrese CI" required>
                                @error('ci')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
                                @error('fecha_nacimiento')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono *</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ingrese teléfono" required>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Parentesco *</label>
                                <select name="parentesco" class="form-control" required>
                                    <option value="">Seleccione parentesco</option>
                                    <option value="padre" {{ old('parentesco') == 'padre' ? 'selected' : '' }}>Padre</option>
                                    <option value="madre" {{ old('parentesco') == 'madre' ? 'selected' : '' }}>Madre</option>
                                    <option value="tutor" {{ old('parentesco') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                    <option value="abuelo" {{ old('parentesco') == 'abuelo' ? 'selected' : '' }}>Abuelo</option>
                                    <option value="abuela" {{ old('parentesco') == 'abuela' ? 'selected' : '' }}>Abuela</option>
                                    <option value="tio" {{ old('parentesco') == 'tio' ? 'selected' : '' }}>Tío</option>
                                    <option value="tia" {{ old('parentesco') == 'tia' ? 'selected' : '' }}>Tía</option>
                                    <option value="hermano" {{ old('parentesco') == 'hermano' ? 'selected' : '' }}>Hermano</option>
                                    <option value="hermana" {{ old('parentesco') == 'hermana' ? 'selected' : '' }}>Hermana</option>
                                </select>
                                @error('parentesco')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ocupación</label>
                                <input type="text" name="ocupacion" class="form-control" value="{{ old('ocupacion') }}" placeholder="Ingrese ocupación">
                                @error('ocupacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <textarea name="direccion" class="form-control" rows="3" placeholder="Ingrese dirección...">{{ old('direccion') }}</textarea>
                                @error('direccion')
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
        #ppffsTable_wrapper .row {
            margin: 0;
        }
        .badge {
            font-size: 0.75em;
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
        var hasData = {{ count($ppffs) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#ppffsTable').DataTable({
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
                        title: 'Listado de Padres de Familia',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Padres de Familia',
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
                        title: 'Listado de Padres de Familia',
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