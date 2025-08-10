@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Datos del sistema</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Bienvenidos a la sección de configuración del sistema</h3>
                </div>

                <div class="card-body">
                    <form action="{{url('/admin/configuracion/create')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                {{-- Espacio vacío o puedes colocar el logo, etc. --}}

                                <div class="form-group">
                                    <label>Logo de la insititución <b>(*)</b></label>
                                   
                                        <input type="file" class="form-control" name="logo"
                                            value="{{ old('logo', $configuracion->logo ?? '') }}"
                                            placeholder="Escriba aquí..." onchange="mostrarImagen(event)" accept="image/*"
                                            required>
                                        <br>
                                        <center>
                                            <img id="preview" src="{{ isset($configuracion) && $configuracion->logo ? url($configuracion->logo) : '' }}" style="max-width: 200px; margin-top: 10px;">
                                        </center>
                                        
                                    @error('logo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <script>
                                    const mostrarImagen = e =>
                                        document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                                </script>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    {{-- Nombre --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="nombre"
                                                    value="{{ old('nombre', $configuracion->nombre ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('nombre')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Descripción --}}
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Descripción <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="descripcion"
                                                    value="{{ old('descripcion', $configuracion->descripcion ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('descripcion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Dirección --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dirección <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="direccion"
                                                    value="{{ old('direccion', $configuracion->direccion ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('direccion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Teléfono --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Teléfono <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="telefono"
                                                    value="{{ old('telefono', $configuracion->telefono ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('telefono')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Divisa --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Divisa <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-money-bill-wave"></i></span>
                                                </div>
                                                <select name="divisa" class="form-control" required>
                                                    <option value="">Seleccione una opción</option>
                                                    @foreach ($divisas as $divisa)
                                                        <option value="{{ $divisa['symbol'] }}"
                                                            {{ old('divisa', $configuracion->divisa ?? '') == $divisa['symbol'] ? 'selected' : '' }}>
                                                            {{ $divisa['name'] . ' (' . $divisa['symbol'] . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('divisa')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Correo electrónico --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo Electrónico <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control" name="correo_electronico"
                                                    value="{{ old('correo_electronico', $configuracion->correo_electronico ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('correo_electronico')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sitio Web</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="web"
                                                    value="{{ old('web', $configuracion->web ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('web')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div> {{-- col-md-8 --}}
                        </div> {{-- row --}}
                        <hr>
                            <div class="row">
                                    {{-- Correo electrónico --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a href="{{ url('/admin')}}" class="btn btn-default"><i class="fas fa-arrow-left"></i>Cancelar</a>
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
