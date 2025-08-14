@extends('adminlte::page')

@section('title', 'Dashboard Cajero')

@section('content_header')
    <h1><b>Bienvenido: CAJERO/A</b> - {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cash-register mr-2"></i>Datos del usuario</h3>
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
                                <td style="background-color: #f8f9fa; font-weight: bold;">Tipo</td>
                                <td><span class="badge badge-warning">{{ $datos_usuario['tipo'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Estadísticas de Pagos</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $estadisticas['pagos_hoy'] }}</h3>
                                    <p>Pagos de Hoy</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Bs. {{ number_format($estadisticas['monto_hoy'], 2) }}</h3>
                                    <p>Monto de Hoy</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $estadisticas['pagos_mes'] }}</h3>
                                    <p>Pagos del Mes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Bs. {{ number_format($estadisticas['monto_mes'], 2) }}</h3>
                                    <p>Monto del Mes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4><i class="icon fas fa-info"></i> Información</h4>
                Como cajero/a, puedes acceder al módulo de <strong>Pagos</strong> desde el menú lateral para gestionar los pagos de los estudiantes.
            </div>
        </div>
    </div>
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
        .small-box {
            border-radius: 10px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .small-box > .inner {
            padding: 10px;
        }
        .small-box > .small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: #fff;
            color: rgba(255,255,255,0.8);
            display: block;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            text-decoration: none;
        }
        .small-box > .small-box-footer:hover {
            color: #fff;
            background: rgba(0,0,0,0.15);
        }
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box p {
            font-size: 1rem;
        }
        .small-box p > small {
            display: block;
            color: #f9f9f9;
            font-size: 13px;
            margin-top: 5px;
        }
        .small-box h3,
        .small-box p {
            z-index: 5;
        }
        .small-box .icon {
            -webkit-transition: all .3s linear;
            -o-transition: all .3s linear;
            transition: all .3s linear;
            position: absolute;
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 90px;
            color: rgba(0,0,0,0.15);
        }
        .small-box:hover {
            text-decoration: none;
            color: #f9f9f9;
        }
        .small-box:hover .icon {
            font-size: 95px;
        }
        .bg-info {
            background-color: #17a2b8 !important;
            color: #fff !important;
        }
        .bg-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }
        .bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
    </style>
@stop
