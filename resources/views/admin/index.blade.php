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
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop