@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Calificaciones</h1>
                <p class="text-muted">Estudiante: {{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mis Calificaciones</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @forelse($matriculaciones as $matriculacion)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-book mr-2"></i>
                            {{ $matriculacion->asignacion->materia->nombre }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $matriculacion->asignacion->gestion->nombre }}</span>
                            <span class="badge badge-secondary">{{ $matriculacion->asignacion->nivel->nombre }}</span>
                            <span class="badge badge-primary">{{ $matriculacion->asignacion->grado->nombre }} "{{ $matriculacion->asignacion->paralelo->nombre }}"</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Docente:</strong> {{ $matriculacion->asignacion->docente->nombre }} {{ $matriculacion->asignacion->docente->paterno }}
                            </div>
                            <div class="col-md-6">
                                <strong>Turno:</strong> {{ $matriculacion->asignacion->turno->nombre }}
                            </div>
                        </div>

                        @if(isset($calificaciones[$matriculacion->asignacion_id]) && $calificaciones[$matriculacion->asignacion_id]->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Periodo</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Nota</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalNotas = 0;
                                        $cantidadNotas = 0;
                                    @endphp
                                    @foreach($calificaciones[$matriculacion->asignacion_id] as $calificacion)
                                    @php
                                        $totalNotas += $calificacion->nota;
                                        $cantidadNotas++;
                                    @endphp
                                    <tr>
                                        <td>{{ $calificacion->calificacion->periodo->nombre }}</td>
                                        <td>{{ $calificacion->calificacion->tipo }}</td>
                                        <td>{{ $calificacion->calificacion->descripcion ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($calificacion->calificacion->fecha)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $calificacion->nota >= 60 ? 'success' : 'danger' }}">
                                                {{ number_format($calificacion->nota, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($calificacion->nota >= 60)
                                                <i class="fas fa-check-circle text-success" title="Aprobado"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger" title="Reprobado"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($cantidadNotas > 0)
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="4">Promedio General</th>
                                        <th class="text-center">
                                            @php
                                                $promedio = $totalNotas / $cantidadNotas;
                                            @endphp
                                            <span class="badge badge-{{ $promedio >= 60 ? 'success' : 'danger' }} badge-lg">
                                                {{ number_format($promedio, 2) }}
                                            </span>
                                        </th>
                                        <th class="text-center">
                                            @if($promedio >= 60)
                                                <i class="fas fa-trophy text-warning" title="Aprobado"></i>
                                            @else
                                                <i class="fas fa-exclamation-triangle text-danger" title="Reprobado"></i>
                                            @endif
                                        </th>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay calificaciones registradas para esta materia.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                        <h4>No hay matriculaciones activas</h4>
                        <p class="text-muted">No tienes materias asignadas en este momento.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.badge-lg {
    font-size: 1em;
    padding: 0.5em 0.75em;
}
</style>
@endsection