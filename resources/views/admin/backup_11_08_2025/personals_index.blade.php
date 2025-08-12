@extends('adminlte::page')

@section('content_header')
    <h1>Listado del personal {{ $tipo ? $tipo : 'registrado' }}</h1>
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
                    <span class="ml-2">Personal</span>
                </div>
                
                <div class="d-flex align-items-center">
                    <label for="buscar" class="mr-2 mb-0">Buscador:</label>
                    <input type="text" id="buscar" class="form-control form-control-sm" style="width: 200px;">
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Crear nuevo personal
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
                    <table id="personalTable" class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Nro</th>
                                <th>Rol</th>
                                <th>Apellidos y nombres</th>
                                <th>Carnet de identidad</th>
                                <th>Fecha de nacimiento</th>
                                <th>Teléfono</th>
                                <th>Profesión</th>
                                <th>Correo</th>
                                <th>Foto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($personals as $index => $personal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($personal->usuario && $personal->usuario->roles->isNotEmpty())
                                            <span class="badge badge-primary">
                                                {{ strtoupper($personal->usuario->getRoleNames()->first()) }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sin rol</span>
                                        @endif
                                    </td>
                                    <td>{{ $personal->paterno }} {{ $personal->materno }} {{ $personal->nombre }}</td>
                                    <td>{{ $personal->ci }}</td>
                                    <td>{{ $personal->fecha_nacimiento ? \Carbon\Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</td>
                                    <td>{{ $personal->telefono }}</td>
                                    <td>{{ $personal->profesion }}</td>
                                    <td>{{ $personal->usuario ? $personal->usuario->email : 'N/A' }}</td>
                                    <td class="text-center">
                                        @if ($personal->foto)
                                            <img src="{{ asset('uploads/personal/fotos/' . $personal->foto) }}" 
                                                 alt="Foto" width="40" height="40" class="rounded">
                                        @else
                                            <span class="text-muted">Sin foto</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Botón Formaciones -->
                                        <a href="{{ route('admin.formacion.index', $personal->id) }}" 
   class="btn btn-warning btn-sm mr-1" 
   title="Formaciones">
    <i class="fas fa-graduation-cap"></i>
</a>
                                        
                                        <!-- Botón Ver -->
                                        <button type="button" class="btn btn-info btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalVer{{ $personal->id }}" 
                                                title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-1" 
                                                data-toggle="modal" data-target="#ModalUpdate{{ $personal->id }}" 
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('admin.personal.destroy', $personal->id) }}" 
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
                                <div class="modal fade" id="ModalVer{{ $personal->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalVerLabel{{ $personal->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="ModalVerLabel{{ $personal->id }}">Información del Personal</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center mb-3">
                                                        @if($personal->foto)
                                                            <img src="{{ asset('uploads/personal/fotos/' . $personal->foto) }}" 
                                                                 alt="Foto" width="120" height="120" class="rounded-circle">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                                 style="width: 120px; height: 120px;">
                                                                <i class="fas fa-user fa-3x text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Datos Personales</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Nombres:</strong> {{ $personal->nombre }}</p>
                                                                <p><strong>Apellidos:</strong> {{ $personal->paterno }} {{ $personal->materno }}</p>
                                                                <p><strong>Cédula de Identidad:</strong> {{ $personal->ci }}</p>
                                                                <p><strong>Fecha de Nacimiento:</strong> {{ $personal->fecha_nacimiento ? \Carbon\Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</p>
                                                                <p><strong>Edad:</strong> 
                                                                    @if($personal->fecha_nacimiento)
                                                                        {{ \Carbon\Carbon::parse($personal->fecha_nacimiento)->age }} años
                                                                    @else
                                                                        No calculable
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-briefcase mr-2"></i>Datos Profesionales</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Rol:</strong> 
                                                                    @if($personal->usuario && $personal->usuario->roles->isNotEmpty())
                                                                        <span class="badge badge-primary">{{ strtoupper($personal->usuario->getRoleNames()->first()) }}</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Sin rol</span>
                                                                    @endif
                                                                </p>
                                                                <p><strong>Tipo:</strong> {{ ucfirst($personal->tipo) }}</p>
                                                                <p><strong>Profesión:</strong> {{ $personal->profesion }}</p>
                                                                <p><strong>Email:</strong> {{ $personal->usuario ? $personal->usuario->email : 'N/A' }}</p>
                                                                <p><strong>Teléfono:</strong> {{ $personal->telefono }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Dirección</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="mb-0">{{ $personal->direccion }}</p>
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

                                <!-- Modal Formaciones -->
                                <div class="modal fade" id="ModalFormaciones{{ $personal->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalFormacionesLabel{{ $personal->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title" id="ModalFormacionesLabel{{ $personal->id }}">
                                                    <i class="fas fa-graduation-cap mr-2"></i>Formaciones de {{ $personal->nombre }} {{ $personal->paterno }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Información del personal -->
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <div class="card card-outline card-info">
                                                            <div class="card-header">
                                                                <h6 class="mb-0">Datos registrados</h6>
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
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-user-tag mr-1"></i>Rol:</label>
                                                                                    <p class="mb-0">
                                                                                        @if($personal->usuario && $personal->usuario->roles->isNotEmpty())
                                                                                            <span class="badge badge-primary">{{ strtoupper($personal->usuario->getRoleNames()->first()) }}</span>
                                                                                        @else
                                                                                            <span class="badge badge-secondary">Sin rol</span>
                                                                                        @endif
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-user mr-1"></i>Nombres:</label>
                                                                                    <p class="mb-0">{{ $personal->nombre }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-users mr-1"></i>Apellidos:</label>
                                                                                    <p class="mb-0">{{ $personal->paterno }} {{ $personal->materno }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-id-card mr-1"></i>Cédula:</label>
                                                                                    <p class="mb-0">{{ $personal->ci }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-calendar-alt mr-1"></i>Fecha de Nacimiento:</label>
                                                                                    <p class="mb-0">{{ $personal->fecha_nacimiento ? \Carbon\Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-phone mr-1"></i>Teléfono:</label>
                                                                                    <p class="mb-0">{{ $personal->telefono }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-briefcase mr-1"></i>Profesión:</label>
                                                                                    <p class="mb-0">{{ $personal->profesion }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-envelope mr-1"></i>Email:</label>
                                                                                    <p class="mb-0">{{ $personal->usuario ? $personal->usuario->email : 'N/A' }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label><i class="fas fa-map-marker-alt mr-1"></i>Dirección:</label>
                                                                                    <p class="mb-0">{{ $personal->direccion }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Lista de formaciones -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-outline card-warning">
                                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">Formaciones registradas</h6>
                                                                <button type="button" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-plus mr-1"></i>Registrar nuevo
                                                                </button>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="alert alert-info text-center">
                                                                    <i class="fas fa-info-circle mr-2"></i>
                                                                    No hay formaciones registradas para este personal.
                                                                    <br>
                                                                    <small>Utiliza el botón "Registrar nuevo" para agregar formaciones.</small>
                                                                </div>
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
                                <div class="modal fade" id="ModalUpdate{{ $personal->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $personal->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="ModalUpdateLabel{{ $personal->id }}">Editar Personal</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.personal.update', $personal->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Fotografía</label>
                                                                <input type="file" name="foto" class="form-control-file" accept="image/*">
                                                                @if($personal->foto)
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('uploads/personal/fotos/' . $personal->foto) }}" alt="Foto actual" width="60" height="60" class="rounded">
                                                                        <small class="d-block text-muted">Foto actual</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Rol del Personal *</label>
                                                                <select name="rol" class="form-control" required>
                                                                    @foreach($roles as $role)
                                                                        <option value="{{ $role->name }}" 
                                                                            {{ ($personal->usuario && $personal->usuario->hasRole($role->name)) ? 'selected' : '' }}>
                                                                            {{ $role->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombres *</label>
                                                                <input type="text" name="nombre" class="form-control" value="{{ $personal->nombre }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Apellidos *</label>
                                                                <input type="text" name="apellidos" class="form-control" value="{{ $personal->paterno . ' ' . $personal->materno }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Cédula de Identidad *</label>
                                                                <input type="text" name="ci" class="form-control" value="{{ $personal->ci }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Fecha de Nacimiento *</label>
                                                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $personal->fecha_nacimiento ? $personal->fecha_nacimiento->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Teléfono *</label>
                                                                <input type="text" name="telefono" class="form-control" value="{{ $personal->telefono }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Profesión *</label>
                                                                <input type="text" name="profesion" class="form-control" value="{{ $personal->profesion }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email *</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $personal->usuario ? $personal->usuario->email : '' }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tipo *</label>
                                                                <select name="tipo" class="form-control" required>
                                                                    <option value="docente" {{ $personal->tipo == 'docente' ? 'selected' : '' }}>Docente</option>
                                                                    <option value="administrativo" {{ $personal->tipo == 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Dirección *</label>
                                                                <textarea name="direccion" class="form-control" rows="3" required>{{ $personal->direccion }}</textarea>
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
                                    <td colspan="10" class="text-center">
                                        @if($tipo)
                                            No hay personal de tipo {{ $tipo }} registrado.
                                        @else
                                            No hay personal registrado.
                                        @endif
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
                <h5 class="modal-title" id="ModalCreateLabel">Creación de un nuevo personal {{ $tipo ?: 'administrativo' }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.personal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Llene los datos del formulario</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fotografía (*)</label>
                                <input type="file" name="foto" class="form-control-file" accept="image/*">
                                <small class="text-muted">Seleccionar archivo | foto.jpg</small>
                                @error('foto')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del rol (*)</label>
                                <select name="rol" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('rol') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombres (*)</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ingrese nombres" required>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellidos (*)</label>
                                <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" placeholder="Ingrese apellidos" required>
                                @error('apellidos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cédula de Identidad (*)</label>
                                <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" placeholder="Ingrese CI..." required>
                                @error('ci')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Nacimiento (*)</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                                @error('fecha_nacimiento')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono (*)</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ingrese teléfono" required>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Profesión (*)</label>
                                <input type="text" name="profesion" class="form-control" value="{{ old('profesion') }}" placeholder="Ingrese profesión" required>
                                @error('profesion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email (*)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Ingrese su email..." required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo (*)</label>
                                <select name="tipo" class="form-control" required>
                                    <option value="">Seleccione tipo</option>
                                    <option value="docente" {{ ($tipo == 'docente' || old('tipo') == 'docente') ? 'selected' : '' }}>Docente</option>
                                    <option value="administrativo" {{ ($tipo == 'administrativo' || old('tipo') == 'administrativo' || (!$tipo && !old('tipo'))) ? 'selected' : '' }}>Administrativo</option>
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dirección (*)</label>
                                <textarea name="direccion" class="form-control" rows="2" placeholder="Ingrese dirección..." required>{{ old('direccion') }}</textarea>
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
        .table-responsive {
            overflow-x: auto;
        }
        #personalTable_wrapper .row {
            margin: 0;
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
        // Verificar si hay datos en la tabla
        var hasData = $('#personalTable tbody tr').length > 0 && 
                      !$('#personalTable tbody tr:first td').hasClass('dataTables_empty') &&
                      $('#personalTable tbody tr:first td').attr('colspan') != '10';
        
        var table;
        
        if (hasData) {
            // Inicializar DataTable solo si hay datos
            table = $('#personalTable').DataTable({
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
                        title: 'Listado de Personal {{ $tipo ? ucfirst($tipo) : "" }}',
                        exportOptions: {
                            columns: ':not(:last-child):not(:nth-last-child(2))'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'd-none',
                        title: 'Listado de Personal {{ $tipo ? ucfirst($tipo) : "" }}',
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
                        title: 'Listado de Personal {{ $tipo ? ucfirst($tipo) : "" }}',
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