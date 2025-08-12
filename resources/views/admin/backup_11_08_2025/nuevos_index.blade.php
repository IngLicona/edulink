@extends('adminlte::page')

@section('content_header')
    <h1>Listado de estudiantes nuevos</h1>
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
                    <span class="ml-2">Estudiantes</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                @createButton(['module' => 'estudiantes'])
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                        Registrar nuevo estudiante
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
                    <table id="estudiantesTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Estado</th>
                                <th>Apellidos y nombres</th>
                                <th>CI</th>
                                <th>Fecha de nacimiento</th>
                                <th>Género</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>PPFF</th>
                                <th>Foto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estudiantes as $index => $estudiante)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($estudiante->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </td>
                                    <td>{{ $estudiante->paterno }} {{ $estudiante->materno }} {{ $estudiante->nombre }}</td>
                                    <td>{{ $estudiante->ci }}</td>
                                    <td>{{ $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</td>
                                    <td>
                                        @if($estudiante->genero == 'masculino')
                                            <i class="fas fa-mars text-primary"></i> Masculino
                                        @else
                                            <i class="fas fa-venus text-pink"></i> Femenino
                                        @endif
                                    </td>
                                    <td>{{ $estudiante->telefono }}</td>
                                    <td>{{ $estudiante->usuario ? $estudiante->usuario->email : 'N/A' }}</td>
                                    <td>
                                        @if($estudiante->ppff)
                                            <small>{{ $estudiante->ppff->nombre_completo }}</small>
                                        @else
                                            <span class="text-muted">Sin PPFF</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($estudiante->foto)
                                            <img src="{{ asset('uploads/estudiantes/fotos/' . $estudiante->foto) }}" 
                                                 alt="Foto" width="40" height="40" class="rounded">
                                        @else
                                            <span class="text-muted">Sin foto</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Botón Ver -->
                                        <button type="button" class="btn btn-info btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $estudiante->id }}" 
                                                title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón Editar -->
                                        @editButton(['module' => 'estudiantes'])
                                            <button type="button" class="btn btn-success btn-sm mr-1" 
                                                    data-toggle="modal" data-target="#ModalUpdate{{ $estudiante->id }}" 
                                                    title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endeditButton

                                        <!-- Botón Eliminar -->
                                        @deleteButton(['module' => 'estudiantes'])
                                            <form action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" 
                                                  method="POST" class="form-eliminar d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @enddeleteButton

                                        @noActions(['module' => 'estudiantes'])
                                            <span class="text-muted small">Sin acciones disponibles</span>
                                        @endnoActions
                                    </td>
                                </tr>

                                <!-- Modal Ver -->
                                <div class="modal fade" id="ModalVer{{ $estudiante->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $estudiante->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="ModalVerLabel{{ $estudiante->id }}">Información del Estudiante</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center mb-3">
                                                        @if($estudiante->foto)
                                                            <img src="{{ asset('uploads/estudiantes/fotos/' . $estudiante->foto) }}" 
                                                                 alt="Foto" width="120" height="120" class="rounded-circle">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                                 style="width: 120px; height: 120px;">
                                                                <i class="fas fa-user-graduate fa-3x text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Datos del Estudiante</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Nombres:</strong> {{ $estudiante->nombre }}</p>
                                                                <p><strong>Apellidos:</strong> {{ $estudiante->paterno }} {{ $estudiante->materno }}</p>
                                                                <p><strong>Cédula de Identidad:</strong> {{ $estudiante->ci }}</p>
                                                                <p><strong>Fecha de Nacimiento:</strong> {{ $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                                                                <p><strong>Edad:</strong> 
                                                                    @if($estudiante->fecha_nacimiento)
                                                                        {{ $estudiante->edad }} años
                                                                    @else
                                                                        No calculable
                                                                    @endif
                                                                </p>
                                                                <p><strong>Género:</strong> {{ ucfirst($estudiante->genero) }}</p>
                                                                <p><strong>Teléfono:</strong> {{ $estudiante->telefono }}</p>
                                                                <p><strong>Email:</strong> {{ $estudiante->usuario ? $estudiante->usuario->email : 'N/A' }}</p>
                                                                <p><strong>Estado:</strong> 
                                                                    @if($estudiante->estado == 'activo')
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
                                                                @if($estudiante->ppff)
                                                                    <p><strong>Nombres:</strong> {{ $estudiante->ppff->nombre }}</p>
                                                                    <p><strong>Apellidos:</strong> {{ $estudiante->ppff->paterno }} {{ $estudiante->ppff->materno }}</p>
                                                                    <p><strong>Fecha de Nacimiento:</strong> {{ $estudiante->ppff->fecha_nacimiento ? $estudiante->ppff->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                                                                    <p><strong>Parentesco:</strong> {{ ucfirst($estudiante->ppff->parentesco) }}</p>
                                                                    <p><strong>Ocupación:</strong> {{ $estudiante->ppff->ocupacion }}</p>
                                                                    <p><strong>Teléfono:</strong> {{ $estudiante->ppff->telefono }}</p>
                                                                    <p><strong>Dirección:</strong> {{ $estudiante->ppff->direccion }}</p>
                                                                @else
                                                                    <p class="text-muted">Sin información de PPFF registrada</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Dirección del Estudiante</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="mb-0">{{ $estudiante->direccion }}</p>
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
                                @modalEdit(['module' => 'estudiantes'])
                                    <div class="modal fade" id="ModalUpdate{{ $estudiante->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $estudiante->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="ModalUpdateLabel{{ $estudiante->id }}">Editar Estudiante</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <!-- Datos del Estudiante -->
                                                        <div class="card card-outline card-primary mb-3">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Datos del Estudiante</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Fotografía</label>
                                                                            <input type="file" name="foto" class="form-control-file" accept="image/*">
                                                                            @if($estudiante->foto)
                                                                                <div class="mt-2">
                                                                                    <img src="{{ asset('uploads/estudiantes/fotos/' . $estudiante->foto) }}" alt="Foto actual" width="60" height="60" class="rounded">
                                                                                    <small class="d-block text-muted">Foto actual</small>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Estado *</label>
                                                                            <select name="estado" class="form-control" required>
                                                                                <option value="activo" {{ $estudiante->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                                                                <option value="inactivo" {{ $estudiante->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Nombres *</label>
                                                                            <input type="text" name="nombre" class="form-control" value="{{ $estudiante->nombre }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Apellido Paterno *</label>
                                                                            <input type="text" name="paterno" class="form-control" value="{{ $estudiante->paterno }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Apellido Materno</label>
                                                                            <input type="text" name="materno" class="form-control" value="{{ $estudiante->materno }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Cédula de Identidad *</label>
                                                                            <input type="text" name="ci" class="form-control" value="{{ $estudiante->ci }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Fecha de Nacimiento *</label>
                                                                            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : '' }}" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Género *</label>
                                                                            <select name="genero" class="form-control" required>
                                                                                <option value="masculino" {{ $estudiante->genero == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                                                                <option value="femenino" {{ $estudiante->genero == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label>Teléfono</label>
                                                                            <input type="text" name="telefono" class="form-control" value="{{ $estudiante->telefono }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Dirección *</label>
                                                                            <textarea name="direccion" class="form-control" rows="2" required>{{ $estudiante->direccion }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Datos del PPFF -->
                                                        <div class="card card-outline card-warning">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Datos del Padre/Madre/Tutor</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Seleccionar PPFF *</label>
                                                                            <select name="ppff_id" class="form-control" required>
                                                                                <option value="">Seleccione un PPFF</option>
                                                                                @foreach($ppffs as $ppff)
                                                                                    <option value="{{ $ppff->id }}" {{ $estudiante->ppffs_id == $ppff->id ? 'selected' : '' }}>
                                                                                        {{ $ppff->nombre_completo }} - {{ $ppff->telefono }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
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
                                @endmodalEdit

                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        No hay estudiantes registrados.
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
@modalCreate(['module' => 'estudiantes'])
    <div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ModalCreateLabel">Registro de nuevo estudiante</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.estudiantes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Llene los datos del formulario</strong>
                        </div>

                        <!-- Datos del Estudiante -->
                        <div class="card card-outline card-primary mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Datos del Estudiante</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fotografía</label>
                                            <input type="file" name="foto" class="form-control-file" accept="image/*">
                                            <small class="text-muted">Seleccionar archivo | foto.jpg</small>
                                            @error('foto')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Estado *</label>
                                            <select name="estado" class="form-control" required>
                                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : 'selected' }}>Activo</option>
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
                                            <label>Nombres *</label>
                                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ingrese nombres" required>
                                            @error('nombre')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Apellido Paterno *</label>
                                            <input type="text" name="paterno" class="form-control" value="{{ old('paterno') }}" placeholder="Apellido paterno" required>
                                            @error('paterno')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cédula de Identidad *</label>
                                            <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" placeholder="Ingrese CI..." required>
                                            @error('ci')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento *</label>
                                            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                                            @error('fecha_nacimiento')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Género *</label>
                                            <select name="genero" class="form-control" required>
                                                <option value="">Seleccione género</option>
                                                <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                            </select>
                                            @error('genero')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ingrese teléfono">
                                            @error('telefono')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Dirección *</label>
                                            <textarea name="direccion" class="form-control" rows="2" placeholder="Ingrese dirección..." required>{{ old('direccion') }}</textarea>
                                            @error('direccion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos del PPFF -->
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Datos del Padre/Madre/Tutor</h6>
                            </div>
                            <div class="card-body">
                                <!-- Opción de PPFF -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Seleccionar opción de PPFF *</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="ppff_option" id="ppff_existing" value="existing" {{ old('ppff_option') == 'existing' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ppff_existing">
                                                    Seleccionar PPFF existente
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="ppff_option" id="ppff_new" value="new" {{ old('ppff_option') == 'new' ? 'checked' : 'checked' }}>
                                                <label class="form-check-label" for="ppff_new">
                                                    Crear nuevo PPFF
                                                </label>
                                            </div>
                                            @error('ppff_option')
                                                <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección para seleccionar PPFF existente -->
                                <div id="existing_ppff_section" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Seleccionar PPFF *</label>
                                                <select name="ppffs_id" class="form-control">
                                                    <option value="">Seleccione un PPFF</option>
                                                    @foreach($ppffs as $ppff)
                                                        <option value="{{ $ppff->id }}" {{ old('ppff_id') == $ppff->id ? 'selected' : '' }}>
                                                            {{ $ppff->nombre_completo }} - {{ $ppff->telefono }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('ppff_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección para crear nuevo PPFF -->
                                <div id="new_ppff_section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nombres del PPFF *</label>
                                                <input type="text" name="ppff_nombre" class="form-control" value="{{ old('ppff_nombre') }}" placeholder="Ingrese nombres del PPFF">
                                                @error('ppff_nombre')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Apellido Paterno *</label>
                                                <input type="text" name="ppff_paterno" class="form-control" value="{{ old('ppff_paterno') }}" placeholder="Apellido paterno">
                                                @error('ppff_paterno')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Apellido Materno</label>
                                                <input type="text" name="ppff_materno" class="form-control" value="{{ old('ppff_materno') }}" placeholder="Apellido materno">
                                                @error('ppff_materno')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cédula de Identidad PPFF *</label>
                                                <input type="text" name="ppff_ci" class="form-control" value="{{ old('ppff_ci') }}" placeholder="Ingrese CI del PPFF">
                                                @error('ppff_ci')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fecha de Nacimiento PPFF</label>
                                                <input type="date" name="ppff_fecha_nacimiento" class="form-control" value="{{ old('ppff_fecha_nacimiento') }}">
                                                @error('ppff_fecha_nacimiento')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Teléfono PPFF *</label>
                                                <input type="text" name="ppff_telefono" class="form-control" value="{{ old('ppff_telefono') }}" placeholder="Ingrese teléfono PPFF">
                                                @error('ppff_telefono')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Parentesco *</label>
                                                <select name="ppff_parentesco" class="form-control">
                                                    <option value="">Seleccione parentesco</option>
                                                    <option value="padre" {{ old('ppff_parentesco') == 'padre' ? 'selected' : '' }}>Padre</option>
                                                    <option value="madre" {{ old('ppff_parentesco') == 'madre' ? 'selected' : '' }}>Madre</option>
                                                    <option value="tutor" {{ old('ppff_parentesco') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                                    <option value="abuelo" {{ old('ppff_parentesco') == 'abuelo' ? 'selected' : '' }}>Abuelo/a</option>
                                                </select>
                                                @error('ppff_parentesco')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Ocupación</label>
                                                <input type="text" name="ppff_ocupacion" class="form-control" value="{{ old('ppff_ocupacion') }}" placeholder="Ingrese ocupación">
                                                @error('ppff_ocupacion')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Dirección PPFF</label>
                                                <textarea name="ppff_direccion" class="form-control" rows="2" placeholder="Ingrese dirección del PPFF...">{{ old('ppff_direccion') }}</textarea>
                                                @error('ppff_direccion')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
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
@endmodalCreate
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <style>
        .rounded {
            object-fit: cover;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .rounded-circle {
            object-fit: cover;
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
        .table-responsive {
            overflow-x: auto;
        }
        #estudiantesTable_wrapper .row {
            margin: 0;
        }
        .text-pink {
            color: #e91e63 !important;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
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
    @showModalOnErrors(['module' => 'estudiantes'])
        <script>
            $(document).ready(function () {
                $('#ModalCreate').modal('show');
            });
        </script>
    @endshowModalOnErrors

    $(document).ready(function() {
        // Control de opciones de PPFF
        function togglePpffSections() {
            var selectedOption = $('input[name="ppff_option"]:checked').val();
            
            if (selectedOption === 'existing') {
                $('#existing_ppff_section').show();
                $('#new_ppff_section').hide();
                
                // Hacer campos de nuevo PPFF no requeridos y limpiar valores
                $('#new_ppff_section input, #new_ppff_section select, #new_ppff_section textarea').each(function() {
                    $(this).removeAttr('required');
                    if ($(this).is('select')) {
                        $(this).val('').trigger('change');
                    } else {
                        $(this).val('');
                    }
                });
                
                // Hacer campo de PPFF existente requerido
                $('select[name="ppffs_id"]').attr('required', true);
                
            } else if (selectedOption === 'new') {
                $('#existing_ppff_section').hide();
                $('#new_ppff_section').show();
                
                // Hacer campo de PPFF existente no requerido y limpiar
                $('select[name="ppffs_id"]').removeAttr('required').val('').trigger('change');
                
                // Hacer campos de nuevo PPFF requeridos
                $('input[name="ppff_nombre"]').attr('required', true);
                $('input[name="ppff_paterno"]').attr('required', true);
                $('input[name="ppff_ci"]').attr('required', true);
                $('input[name="ppff_telefono"]').attr('required', true);
                $('select[name="ppff_parentesco"]').attr('required', true);
            }
        }

        // Event listener para el cambio de opción de PPFF
        $('input[name="ppff_option"]').change(function() {
            togglePpffSections();
        });

        // Inicializar la vista según la opción seleccionada al cargar la página
        togglePpffSections();

        // Validación antes del envío del formulario
        $('#ModalCreate form').on('submit', function(e) {
            var ppffOption = $('input[name="ppff_option"]:checked').val();
            
            // Debugging
            console.log('Opción seleccionada:', ppffOption);
            
            if (ppffOption === 'existing') {
                var ppffId = $('select[name="ppffs_id"]').val();
                console.log('PPFF ID seleccionado:', ppffId);
                
                if (!ppffId || ppffId === '' || ppffId === '0') {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe seleccionar un PPFF existente',
                        icon: 'error'
                    });
                    return false;
                }
            } else if (ppffOption === 'new') {
                var requiredFields = [
                    { name: 'ppff_nombre', label: 'Nombres del PPFF' },
                    { name: 'ppff_paterno', label: 'Apellido Paterno del PPFF' },
                    { name: 'ppff_ci', label: 'CI del PPFF' },
                    { name: 'ppff_telefono', label: 'Teléfono del PPFF' },
                    { name: 'ppff_parentesco', label: 'Parentesco' }
                ];
                
                var emptyFields = [];
                requiredFields.forEach(function(field) {
                    var value = $('input[name="' + field.name + '"], select[name="' + field.name + '"]').val();
                    if (!value || value.trim() === '') {
                        emptyFields.push(field.label);
                    }
                });
                
                if (emptyFields.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe completar los siguientes campos: ' + emptyFields.join(', '),
                        icon: 'error'
                    });
                    return false;
                }
            }
        });

        // Verificar si hay datos para la tabla
        var hasData = {{ count($estudiantes) > 0 ? 'true' : 'false' }};
        var table;

        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#estudiantesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
                "dom": 'Bfrtip',
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 }, // Desactiva ordenamiento en la última columna (Acciones)
                    { "orderable": false, "targets": -2 }  // Desactiva ordenamiento en la penúltima columna (Foto)
                ],
                "buttons": [
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))' // Excluye las últimas 2 columnas (Foto y Acciones)
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'd-none',
                        title: 'Listado de Estudiantes',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Estudiantes',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        className: 'd-none',
                        title: 'Listado de Estudiantes',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))'
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