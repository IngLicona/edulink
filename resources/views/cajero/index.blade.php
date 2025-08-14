@extends('adminlte::page')

@section('title', 'Dashboard Cajero')

@section('content_header')
    <h1><b>Bienvenido Cajero:</b> {{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Personal</h3>
                </div>
                <div class="card-body">
                    @php
                        $personal = Auth::user()->personal;
                    @endphp
                    <p><strong>Nombre:</strong> {{ $personal->nombre_completo ?? 'N/A' }}</p>
                    <p><strong>CI:</strong> {{ $personal->ci ?? 'N/A' }}</p>
                    <p><strong>Cargo:</strong> Cajero</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pagos del Día</h3>
                </div>
                <div class="card-body">
                    <canvas id="pagosHoyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Gráfica de Pagos por Mes -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total de Pagos por Mes (Año Actual)</h3>
                </div>
                <div class="card-body">
                    <canvas id="pagosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfica de Pagos por Mes
        const pagosCtx = document.getElementById('pagosChart').getContext('2d');
        new Chart(pagosCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($pagos_por_mes->pluck('mes')) !!},
                datasets: [{
                    label: 'Total de Pagos',
                    data: {!! json_encode($pagos_por_mes->pluck('total')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfica de Pagos del Día
        const pagosHoyCtx = document.getElementById('pagosHoyChart').getContext('2d');
        new Chart(pagosHoyCtx, {
            type: 'pie',
            data: {
                labels: ['Matrículas', 'Mensualidades', 'Otros'],
                datasets: [{
                    data: {!! json_encode($pagos_hoy ?? [0, 0, 0]) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Pagos del Día'
                    }
                }
            }
        });
    </script>
@stop
