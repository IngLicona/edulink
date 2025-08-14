@extends('adminlte::page')

@section('title', 'Dashboard Estudiante')

@section('content_header')
    <h1><b>Bienvenido: ESTUDIANTE</b> - {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Datos del usuario</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; width: 200px;">Nombre</td>
                                <td>{{ $datos_usuario['nombre'] }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Apellidos</td>
                                <td>{{ $datos_usuario['apellidos'] }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Carnet de identidad</td>
                                <td>{{ $datos_usuario['ci'] }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Fecha de nacimiento</td>
                                <td>{{ $datos_usuario['fecha_nacimiento'] ?? 'No registrada' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Edad</td>
                                <td>{{ $datos_usuario['edad'] ? $datos_usuario['edad'] . ' años' : 'No calculada' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Teléfono</td>
                                <td>{{ $datos_usuario['telefono'] ?? 'No registrado' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Dirección</td>
                                <td>{{ $datos_usuario['direccion'] ?? 'No registrada' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($matriculacion)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap mr-2"></i>Información Académica Actual</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; width: 200px;">Gestión</td>
                                <td><span class="badge badge-primary">{{ $matriculacion->gestion->nombre }}</span></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Nivel</td>
                                <td>{{ $matriculacion->nivel->nombre }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Grado</td>
                                <td>{{ $matriculacion->grado->nombre }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Paralelo</td>
                                <td>{{ $matriculacion->paralelo->nombre }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Turno</td>
                                <td><span class="badge badge-info">{{ $matriculacion->turno->nombre }}</span></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Estado</td>
                                <td>
                                    @if($matriculacion->estado == 'activo')
                                        <span class="badge badge-success">ACTIVO</span>
                                    @else
                                        <span class="badge badge-danger">INACTIVO</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h4><i class="icon fas fa-exclamation-triangle"></i> Sin Matriculación</h4>
                No tienes una matriculación activa en el sistema. Contacta con la administración.
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
    <style>
        .info-box {
            transition: transform 0.3s;
        }
        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-outline {
            border-top: 3px solid;
        }
        .card-outline.card-primary {
            border-top-color: #007bff;
        }
        .card-outline.card-success {
            border-top-color: #28a745;
        }
    </style>
@stop
