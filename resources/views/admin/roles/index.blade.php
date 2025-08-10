@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Roles</h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-10">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Roles registrados</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Crear nuevo rol
                </button>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#ModalUpdate{{ $role->id }}">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </button>

                                    <form action="{{ url('admin/roles/' . $role->id) }}" method="POST" class="form-eliminar d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="ModalUpdate{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel{{ $role->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="ModalUpdateLabel{{ $role->id }}">Editar Rol</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>

                                        <form action="{{ url('admin/roles/' . $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Nombre</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
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
                                <td colspan="3" class="text-center">No hay roles registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ModalCreateLabel">Registrar nuevo rol</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form action="{{ url('admin/roles') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre del rol</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
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

@section('css')
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
