@extends('adminlte::page')

@section('content_header')
<h1>Listado de turnos</h1>
<hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Turnos registrados</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Crear nuevo turno
                </button>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turnos as $turno)
                            <tr>
                                <td>{{ $turno->id }}</td>
                                <td>{{ $turno->nombre }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal"
                                            data-target="#ModalUpdate{{ $turno->id }}">
                                            <i class="fas fa-pencil-alt"></i> Editar
                                        </button>

                                        <form action="{{ url('admin/turnos/' . $turno->id) }}" method="POST"
                                            class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Editar Turno -->
                                    <div class="modal fade" id="ModalUpdate{{ $turno->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="ModalUpdateLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #08a35b; color: white;">
                                                    <h5 class="modal-title" id="ModalUpdateLabel">Editar turno</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ url('admin/turnos/' . $turno->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre del turno <b>(*)</b></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-clock"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control" name="nombre"
                                                                    value="{{ old('nombre', $turno->nombre) }}"
                                                                    placeholder="Escribir aquí..." required>
                                                            </div>
                                                            @error('nombre')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Turno -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #007bff; color: white;">
                <h5 class="modal-title" id="ModalCreateLabel">Registro de un nuevo turno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('admin/turnos/create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del turno <b>(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}"
                                placeholder="Escribir aquí..." required>
                        </div>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <hr>
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
{{-- Puedes agregar tus estilos personalizados aquí si los necesitas --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        $(document).ready(function () {
            $('#ModalCreate').modal('show');
        })
    </script>
@endif

<script>
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function (e) {
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

<script>
    console.log("Modal funcionando correctamente con Bootstrap 4 y AdminLTE.");
</script>
@stop