@extends('adminlte::page')


@section('content_header')
    <h1><b>Creacion de una nueva gestion educativa</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>

                <div class="card-body">
                    <form action="{{url('/admin/gestiones/create')}}" method="POST">
                        @csrf
                        <div class="row">
                              <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nombre <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="number" class="form-control" name="nombre"
                                                    value="{{ old('nombre') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('nombre')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                 </div>
                        <hr>
                            <div class="row">
                                    {{-- Correo electrónico --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a href="{{ url('/admin/gestiones')}}" class="btn btn-default"><i class="fas fa-arrow-left"></i>Cancelar</a>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save">Guardar</i></button>
                                        </div>
                                    </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
