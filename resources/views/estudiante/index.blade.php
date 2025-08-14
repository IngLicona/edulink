@extends('adminlte::page')

@section('title', 'Dashboard Estudiante')

@section('content_header')
    <h1><b>Bienvenido Estudiante:</b> {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        @php
            $estudiante = Auth::user()->estudiante;
            $matriculacion = $estudiante->getMatriculacionActiva();
        @endphp

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Personal</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $estudiante->nombre_completo }}</p>
                    <p><strong>CI:</strong> {{ $estudiante->ci }}</p>
                    <p><strong>Estado:</strong> {{ $estudiante->estado }}</p>
                    @if($matriculacion)
                        <p><strong>Grado:</strong> {{ $matriculacion->paralelo->grado->nombre }}</p>
                        <p><strong>Nivel:</strong> {{ $matriculacion->paralelo->grado->nivel->nombre }}</p>
                        <p><strong>Paralelo:</strong> {{ $matriculacion->paralelo->nombre }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mis Calificaciones</h3>
                </div>
                <div class="card-body">
                    @if($matriculacion)
                        <canvas id="calificacionesChart"></canvas>
                    @else
                        <p>No hay información de calificaciones disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Próximas Actividades</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Aquí puedes agregar las próximas actividades o eventos -->
                        <div class="time-label">
                            <span class="bg-red">{{ now()->format('d M. Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-user bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Bienvenido al nuevo período escolar</h3>
                                <div class="timeline-body">
                                    Estamos contentos de tenerte con nosotros en este nuevo período escolar.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
@if(isset($matriculacion))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Aquí puedes agregar la lógica para mostrar las calificaciones en un gráfico
    const calificacionesCtx = document.getElementById('calificacionesChart').getContext('2d');
    new Chart(calificacionesCtx, {
        type: 'bar',
        data: {
            labels: ['Materia 1', 'Materia 2', 'Materia 3'], // Reemplazar con materias reales
            datasets: [{
                label: 'Calificaciones',
                data: [85, 90, 78], // Reemplazar con calificaciones reales
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endif
@stop
