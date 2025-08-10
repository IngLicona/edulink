@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Autenticarse para iniciar sesión'))

@section('auth_body')

    {{-- Botón de Google estilizado --}}
    <div class="text-center mb-4">
        <a href="{{ route('auth.google') }}" class="btn btn-primary text-white w-100"
            style="max-width: 320px; margin: 0 auto; background-color: #4285F4; border-color: #4285F4;">
            <i class="fab fa-google mr-2"></i> Iniciar sesión con Google
        </a>
    </div>

    <div class="text-center mb-3">
        <small class="text-muted">— o con tu cuenta —</small>
    </div>

    {{-- Login tradicional --}}
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                placeholder="{{ __('Email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>

        {{-- Password --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="{{ __('Contraseña') }}" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        {{-- Recordarme y botón de login correctamente alineado --}}
        {{-- Recordarme y botón de login bien alineado --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">{{ __('Recordarme') }}</label>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success btn-block">
                    {{ __('Acceder') }}
                </button>
            </div>
        </div>

    </form>
@endsection

@section('auth_footer')
    <p class="my-0"><a href="{{ route('password.request') }}">{{ __('Olvidé mi contraseña') }}</a></p>
    @if (Route::has('register'))
    <a href="{{ route('register') }}">Registrarse</a>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


@endsection
