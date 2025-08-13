@extends('adminlte::page')

@section('title', 'Detalle de Calificaciones')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1>Detalle de Calificaciones</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Información del Estudiante</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $estudiante->paterno }} {{ $estudiante->materno }} {{ $estudiante->nombre }}</p>
                    <p><strong>CI:</strong> {{ $estudiante->ci }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                    <p><strong>Docente:</strong> {{ $asignacion->docente->nombre_completo }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nivel:</strong> {{ $asignacion->nivel->nombre }}</p>
                    <p><strong>Grado:</strong> {{ $asignacion->grado->nombre }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Paralelo:</strong> {{ $asignacion->paralelo->nombre }}</p>
                    <p><strong>Gestión:</strong> {{ $asignacion->gestion->nombre }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3>Calificaciones por Periodo</h3>
        </div>
        <div class="card-body">
            @if($calificaciones->count() > 0)
                @php
                    $periodoActual = null;
                    $promedioTotal = 0;
                    $contadorPeriodos = 0;
                @endphp

                @foreach($calificaciones->sortBy('calificacion.periodo_id') as $detalle)
                    @if($periodoActual !== $detalle->calificacion->periodo_id)
                        @if($periodoActual !== null)
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Promedio del Periodo:</strong></td>
                                    <td class="text-center"><strong>{{ $promediosPorPeriodo[$periodoActual] }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        @endif

                        <h4 class="mt-4">{{ $detalle->calificacion->periodo->nombre }}</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Calificación</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                        @php
                            $periodoActual = $detalle->calificacion->periodo_id;
                        @endphp
                    @endif

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($detalle->calificacion->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $detalle->calificacion->tipo }}</td>
                        <td>{{ $detalle->calificacion->descripcion }}</td>
                        <td class="text-center">{{ $detalle->nota }}</td>
                        <td>{{ $detalle->observaciones ?? '-' }}</td>
                    </tr>
                @endforeach

                @if($periodoActual !== null)
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Promedio del Periodo:</strong></td>
                            <td class="text-center"><strong>{{ $promediosPorPeriodo[$periodoActual] }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @endif

                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <h4>Resumen de Calificaciones</h4>
                        <div class="row">
                            @foreach($promediosPorPeriodo as $periodoId => $promedio)
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Periodo {{ $loop->iteration }}</span>
                                            <span class="info-box-number">{{ $promedio }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-3">
                                <div class="info-box bg-success">
                                    <div class="info-box-content">
                                        <span class="info-box-text">Promedio Final</span>
                                        <span class="info-box-number">{{ $promedioGeneral }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    No hay calificaciones registradas para este estudiante en esta asignación.
                </div>
            @endif
        </div>
    </div>

    <div class="text-right mt-4 mb-4">
        <a href="{{ route('admin.calificaciones.show_admin', $asignacion->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .info-box {
            min-height: 100px;
            background: #fff;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 0.25rem;
            margin-bottom: 20px;
            padding: 15px;
        }
        .info-box-content {
            padding: 5px 10px;
            margin-left: 0;
        }
        .info-box-text {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }
        .bg-success {
            background-color: #28a745!important;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Aquí puedes agregar cualquier JavaScript necesario
        });
    </script>
@stop
