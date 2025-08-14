@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><b>Bienvenido: {{Auth::user()->roles->pluck('name')->implode(', ')}}</b> - {{ Auth::user()->name }}</h1>
    <hr>

@stop

@section('content')
    <div class="row">

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/colegio.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Gestiones registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_gestiones}} gestiones</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/calendario.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Periodos registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_periodos}} periodos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/lista.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Niveles registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_niveles}} niveles</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/cliente.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Grados registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_grados}} grados</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/redaccion.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Paralelos registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_paralelos}} paralelos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/tiempo.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Turnos registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_turnos}} turnos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/libro-abierto.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Materias registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_materias}} Materias</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/roles.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Roles registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_roles}} Roles</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/ayudante-administrativo.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Administrativos registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_personal_administrativo}} Administrativos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/capacitacion.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Docentes registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_personal_docente}} Docentes</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/ppff.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Padres de familia registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_ppff}} Padres de Familia</span>
                </div>
            </div>
        </div>


        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{url('/img/estudiantes.gif')}}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Estudiantes registrados</b></span>
                    <span class="info-box-number" style="color: #1d20fa; font-size:15pt">{{$total_estudiantes}} Estudiantes</span>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">
        <!-- Gráfica de Estudiantes Matriculados -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total de Estudiantes Matriculados por Año</h3>
                </div>
                <div class="card-body">
                    <canvas id="matriculadosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de Pagos por Mes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total de Pagos por Mes</h3>
                </div>
                <div class="card-body">
                    <canvas id="pagosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfica de Estudiantes Matriculados
        const matriculadosCtx = document.getElementById('matriculadosChart').getContext('2d');
        const matriculadosData = {!! json_encode($estudiantes_por_anio) !!};
        console.log('Datos de matriculados:', matriculadosData); // Para depuración
        new Chart(matriculadosCtx, {
            type: 'bar', // Cambiado a barras para mejor visualización
            data: {
                labels: matriculadosData.map(item => item.anio),
                datasets: [{
                    label: 'Estudiantes Matriculados',
                    data: matriculadosData.map(item => parseInt(item.total)),
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
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Estudiantes Matriculados por Año'
                    }
                }
            }
        });

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
    </script>
@stop