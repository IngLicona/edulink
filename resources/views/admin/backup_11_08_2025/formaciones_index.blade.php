@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Formaciones de {{ $personal->nombre_completo }}</h1>
        <a href="{{ route('admin.personal.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Personal
        </a>
    </div>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Información del personal -->
        <div class="card card-outline card-info mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Información del Personal</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        @if($personal->foto)
                            <img src="{{ asset('uploads/personal/fotos/' . $personal->foto) }}" 
                                 alt="Foto" width="80" height="80" class="rounded">
                        @else
                            <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user-tag mr-1"></i>Rol:</strong>
                                    @if($personal->usuario && $personal->usuario->roles->isNotEmpty())
                                        <span class="badge badge-primary">{{ strtoupper($personal->usuario->getRoleNames()->first()) }}</span>
                                    @else
                                        <span class="badge badge-secondary">Sin rol</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user mr-1"></i>Nombres:</strong> {{ $personal->nombre }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-users mr-1"></i>Apellidos:</strong> {{ $personal->paterno }} {{ $personal->materno }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-id-card mr-1"></i>CI:</strong> {{ $personal->ci }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong><i class="fas fa-briefcase mr-1"></i>Profesión:</strong> {{ $personal->profesion }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong><i class="fas fa-envelope mr-1"></i>Email:</strong> {{ $personal->usuario ? $personal->usuario->email : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong><i class="fas fa-phone mr-1"></i>Teléfono:</strong> {{ $personal->telefono }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formaciones -->
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Formaciones Académicas</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    <i class="fas fa-plus mr-1"></i>Registrar Nueva Formación
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

                <div class="table-responsive">
                    <table id="formacionesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Título</th>
                                <th>Institución</th>
                                <th>Nivel</th>
                                <th>Fecha de Graduación</th>
                                <th>Archivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($personal->formaciones as $index => $formacion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $formacion->titulo }}</td>
                                    <td>{{ $formacion->institucion }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $formacion->nivel }}</span>
                                    </td>
                                    <td>{{ $formacion->fecha_graduacion_formateada }}</td>
                                    <td class="text-center">
                                        @if ($formacion->archivo)
                                            <a href="{{ route('admin.formacion.download', $formacion->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Descargar archivo">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Sin archivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalEdit{{ $formacion->id }}" 
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('admin.formacion.destroy', $formacion->id) }}" 
                                              method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="ModalEdit{{ $formacion->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalEditLabel{{ $formacion->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="ModalEditLabel{{ $formacion->id }}">Editar Formación</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.formacion.update', $formacion->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Título de la Formación *</label>
                                                                <input type="text" name="titulo" class="form-control" value="{{ $formacion->titulo }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Institución *</label>
                                                                <input type="text" name="institucion" class="form-control" value="{{ $formacion->institucion }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nivel Académico *</label>
                                                                <select name="nivel" class="form-control" required>
                                                                    <option value="">Seleccione nivel</option>
                                                                    @foreach($niveles as $nivel)
                                                                        <option value="{{ $nivel }}" {{ $formacion->nivel == $nivel ? 'selected' : '' }}>
                                                                            {{ $nivel }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Fecha de Graduación *</label>
                                                                <input type="date" name="fecha_graduacion" class="form-control" value="{{ $formacion->fecha_graduacion->format('Y-m-d') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Archivo (PDF, DOC, DOCX, JPG, PNG - Máx. 5MB)</label>
                                                                <input type="file" name="archivo" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                                @if($formacion->archivo)
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            Archivo actual: {{ $formacion->archivo }}
                                                                            <a href="{{ route('admin.formacion.download', $formacion->id) }}" class="ml-2">
                                                                                <i class="fas fa-download"></i> Descargar
                                                                            </a>
                                                                        </small>
                                                                    </div>
                                                                @endif
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
                                    <td colspan="7" class="text-center">No hay formaciones registradas para este personal.</td>
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
                <h5 class="modal-title" id="ModalCreateLabel">Registrar Nueva Formación</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.formacion.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="personal_id" value="{{ $personal->id }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Complete los datos de la formación académica</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Título de la Formación *</label>
                                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" placeholder="Ej: Licenciatura en Matemáticas" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Institución *</label>
                                <input type="text" name="institucion" class="form-control" value="{{ old('institucion') }}" placeholder="Ej: Universidad Nacional" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nivel Académico *</label>
                                <select name="nivel" class="form-control" required>
                                    <option value="">Seleccione nivel</option>
                                    @foreach($niveles as $nivel)
                                        <option value="{{ $nivel }}" {{ old('nivel') == $nivel ? 'selected' : '' }}>
                                            {{ $nivel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Graduación *</label>
                                <input type="date" name="fecha_graduacion" class="form-control" value="{{ old('fecha_graduacion') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Archivo (PDF, DOC, DOCX, JPG, PNG - Máx. 5MB)</label>
                                <input type="file" name="archivo" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Opcional: Diploma, certificado o documento que respalde la formación.</small>
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
    <style>
        .rounded {
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
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if ($errors->any())
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        });
    @endif

    $(document).ready(function() {
        // Verificar si hay datos en la tabla
        var hasData = $('#formacionesTable tbody tr').length > 0 && 
                      !$('#formacionesTable tbody tr:first td').hasClass('dataTables_empty') &&
                      $('#formacionesTable tbody tr:first td').attr('colspan') != '7';
        
        if (hasData) {
            // Inicializar DataTable solo si hay datos
            $('#formacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 }, // Desactiva ordenamiento en la última columna (Acciones)
                    { "orderable": false, "targets": -2 }  // Desactiva ordenamiento en la penúltima columna (Archivo)
                ],
                "order": [[ 4, "desc" ]] // Ordenar por fecha de graduación descendente
            });
        } else {
            console.log('No hay formaciones para mostrar en la tabla');
        }
    });

    // Confirmación de eliminación con SweetAlert
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción eliminará la formación y su archivo adjunto!",
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