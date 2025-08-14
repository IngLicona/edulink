@extends('adminlte::page')

@section('title', 'Dashboard Docente')

@section('content_header')
    <h1><b>Bienvenido Docente:</b> {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        @php
            $personal = Auth::user()->personal;
            $asignaciones = $personal->asignaciones ?? collect([]);
        @endphp

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Personal</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $personal->nombre_completo ?? 'N/A' }}</p>
                    <p><strong>CI:</strong> {{ $personal->ci ?? 'N/A' }}</p>
                    <p><strong>Profesión:</strong> {{ $personal->profesion ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mis Asignaturas</h3>
                </div>
                <div class="card-body">
                    @if($asignaciones->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Grado</th>
                                        <th>Paralelo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asignaciones as $asignacion)
                                    <tr>
                                        <td>{{ $asignacion->materia->nombre }}</td>
                                        <td>{{ $asignacion->paralelo->grado->nombre }}</td>
                                        <td>{{ $asignacion->paralelo->nombre }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver Detalles</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No hay asignaturas asignadas actualmente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Próximas Evaluaciones</h3>
                </div>
                <div class="card-body">
                    <!-- Aquí puedes agregar un calendario o lista de evaluaciones programadas -->
                    <p>No hay evaluaciones programadas próximamente.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Asistencias Pendientes</h3>
                </div>
                <div class="card-body">
                    <!-- Aquí puedes mostrar las asistencias que faltan por registrar -->
                    <p>No hay registros de asistencia pendientes.</p>
                </div>
            </div>
        </div>
    </div>
@stop
