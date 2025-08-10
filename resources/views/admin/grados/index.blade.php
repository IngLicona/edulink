@extends('adminlte::page')

@section('content_header')
    <h1>Listado de grados</h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-10">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Grados registrados</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                    Crear nuevo grado
                </button>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($niveles) && count($niveles) > 0)
                            @foreach ($niveles as $nivel)
                                <tr class="table-primary">
                                    <td colspan="4"><strong>Nivel: {{ $nivel->nombre }}</strong></td>
                                </tr>

                                @forelse ($nivel->grados as $grado)
                                    <tr>
                                        <td>{{ $grado->id }}</td>
                                        <td>{{ $grado->nombre }}</td>
                                        <td>{{ $nivel->nombre }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal"
                                                data-target="#ModalUpdate{{ $grado->id }}">
                                                <i class="fas fa-pencil-alt"></i> Editar
                                            </button>

                                            <form action="{{ url('admin/grados/' . $grado->id) }}" method="POST" class="form-eliminar d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="ModalUpdate{{ $grado->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalUpdateLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="ModalUpdateLabel">Editar grado</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>

                                                <form action="{{ url('admin/grados/' . $grado->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre" value="{{ $grado->nombre }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nivel_id">Nivel</label>
                                                            <select name="nivel_id" class="form-control" required>
                                                                @if(isset($niveles))
                                                                    @foreach ($niveles as $n)
                                                                        <option value="{{ $n->id }}" {{ $grado->nivel_id == $n->id ? 'selected' : '' }}>
                                                                            {{ $n->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
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
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay grados registrados para este nivel.</td>
                                    </tr>
                                @endforelse
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No hay niveles registrados.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<!-- Modal Crear -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ModalCreateLabel">Registrar nuevo grado</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form action="{{ url('admin/grados/create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del grado</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nivel_id">Nivel</label>
                        <select name="nivel_id" class="form-control" required>
                            <option value="" disabled selected>Seleccione un nivel</option>
                            @if(isset($niveles))
                                @foreach ($niveles as $nivel)
                                    <option value="{{ $nivel->id }}">{{ $nivel->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('nivel_id')
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
        })
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