@extends('adminlte::page')

@section('title', 'Dashboard Docente')

@section('content_header')
    <h1><b>Bienvenido: DOCENTE</b> - {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher mr-2"></i>Datos del usuario</h3>
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
                                <td style="background-color: #f8f9fa; font-weight: bold;">Teléfono</td>
                                <td>{{ $datos_usuario['telefono'] ?? 'No registrado' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Dirección</td>
                                <td>{{ $datos_usuario['direccion'] ?? 'No registrada' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Especialidad</td>
                                <td>{{ $datos_usuario['especialidad'] ?? 'No especificada' }}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold;">Tipo</td>
                                <td><span class="badge badge-success">{{ $datos_usuario['tipo'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($asignaciones->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book mr-2"></i>Mis Asignaciones Actuales</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Gestión</th>
                                    <th>Materia</th>
                                    <th>Nivel</th>
                                    <th>Grado</th>
                                    <th>Paralelo</th>
                                    <th>Turno</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asignaciones as $asignacion)
                                <tr>
                                    <td><span class="badge badge-primary">{{ $asignacion->gestion->nombre }}</span></td>
                                    <td><strong>{{ $asignacion->materia->nombre }}</strong></td>
                                    <td>{{ $asignacion->nivel->nombre }}</td>
                                    <td>{{ $asignacion->grado->nombre }}</td>
                                    <td>{{ $asignacion->paralelo->nombre }}</td>
                                    <td><span class="badge badge-info">{{ $asignacion->turno->nombre }}</span></td>
                                    <td>
                                        @if($asignacion->estado == 'activo')
                                            <span class="badge badge-success">ACTIVO</span>
                                        @else
                                            <span class="badge badge-danger">INACTIVO</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4><i class="icon fas fa-info"></i> Sin Asignaciones</h4>
                Actualmente no tienes asignaciones de materias. Contacta con la administración para más información.
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
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>
@stop
