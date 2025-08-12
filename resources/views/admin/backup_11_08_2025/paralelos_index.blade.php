@extends('adminlte::page')

@section('content_header')
    <h1>Listado de paralelos</h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-10">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Paralelos registrados</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Crear nuevo paralelo
                </button>
            </div>

            <div class="card-body">
                @foreach ($paralelos->groupBy('grado.nivel.nombre') as $nivel => $items)
                    <h5 class="bg-light px-2 py-1 font-weight-bold text-primary">{{ $nivel }}</h5>
                    <table class="table table-bordered table-striped table-hover table-sm mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Grado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $paralelo)
                                <tr>
                                    <td>{{ $paralelo->id }}</td>
                                    <td>{{ $paralelo->nombre }}</td>
                                    <td>{{ $paralelo->grado->nombre }} - {{ $nivel }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal"
                                            data-target="#ModalUpdate{{ $paralelo->id }}">
                                            <i class="fas fa-pencil-alt"></i> Editar
                                        </button>
                                        <form action="{{ url('admin/paralelos/' . $paralelo->id) }}" method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="ModalUpdate{{ $paralelo->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Editar paralelo</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>

                                            <form action="{{ url('admin/paralelos/' . $paralelo->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre</label>
                                                        <input type="text" class="form-control" name="nombre" value="{{ $paralelo->nombre }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="grado_id">Grado</label>
                                                        <select name="grado_id" class="form-control" required>
                                                            @foreach ($gradosAgrupados as $nivel => $grados)
                                                                <optgroup label="{{ $nivel }}">
                                                                    @foreach ($grados as $grado)
                                                                        <option value="{{ $grado->id }}" {{ $paralelo->grado_id == $grado->id ? 'selected' : '' }}>
                                                                            {{ $grado->nombre }} - {{ $nivel }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
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
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar nuevo paralelo</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form action="{{ url('admin/paralelos/create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del paralelo</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="grado_id">Grado</label>
                        <select name="grado_id" class="form-control" required>
                            <option value="" disabled selected>Seleccione un grado</option>
                            @foreach ($gradosAgrupados as $nivel => $grados)
                                <optgroup label="{{ $nivel }}">
                                    @foreach ($grados as $grado)
                                        <option value="{{ $grado->id }}">{{ $grado->nombre }} - {{ $nivel }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('grado_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        });
    </script>
@endif

@if(session('mensaje'))
<script>
    Swal.fire({
        icon: '{{ session("icono") ?? "success" }}',
        title: '{{ session("mensaje") }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

<script>
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
