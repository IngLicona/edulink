@extends('adminlte::page')


@section('content_header')
    <h1>Listado de materias</h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Materias registradas</h3>

                <!-- Botón para abrir el modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaMateria">
                    Crear nueva materia
                </button>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materias as $index => $materia)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ strtoupper($materia->nombre) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal"
                                            data-target="#modalEditarMateria{{ $materia->id }}">
                                            <i class="fas fa-pencil-alt"></i> Editar
                                        </button>

                                        <!-- Formulario Eliminar -->
                                        <form action="{{ route('materias.destroy', $materia->id) }}" method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditarMateria{{ $materia->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modalEditarMateriaLabel{{ $materia->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #08a35b; color: white;">
                                                    <h5 class="modal-title" id="modalEditarMateriaLabel{{ $materia->id }}">
                                                        Editar materia
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('materias.update', $materia->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre de la materia <b>(*)</b></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control" name="nombre"
                                                                    value="{{ old('nombre', $materia->nombre) }}"
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

<!-- Modal Crear Materia -->
<div class="modal fade" id="modalNuevaMateria" tabindex="-1" role="dialog" aria-labelledby="modalNuevaMateriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header" style="background-color: #007bff; color: white;">
                <h5 class="modal-title" id="modalNuevaMateriaLabel">Registro de una nueva materia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.materias.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre de la materia <b>(*)</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-book"></i></span>
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
    {{-- Agrega estilos personalizados si es necesario --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
        <script>
            $(document).ready(function () {
                $('#modalNuevaMateria').modal('show');
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

    <script>
        console.log("Modal de materias funcionando correctamente.");
    </script>
@stop
