@extends('adminlte::page')

@section('content_header')
    <h1>Permisos para el rol - {{ $rol->name }}</h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Permisos registrados del sistema</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" id="selectAll">
                        <i class="fas fa-check-square"></i> Seleccionar Todo
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" id="deselectAll">
                        <i class="far fa-square"></i> Deseleccionar Todo
                    </button>
                </div>
            </div>

            @can('admin.roles.permisos')
            <form action="{{ route('admin.roles.asignar-permisos', $rol->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @foreach($permisosPorCategoria as $categoria => $permisos)
                            @if(count($permisos) > 0)
                            <div class="col-md-4 mb-4">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h6 class="card-title text-primary font-weight-bold">
                                            <i class="fas fa-folder"></i> {{ $categoria }}
                                        </h6>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-xs btn-outline-primary select-category" data-category="{{ $loop->index }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        @foreach($permisos as $permiso)
                                            <div class="form-check mb-1" data-category="{{ $loop->parent->index }}">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="permisos[]" 
                                                       value="{{ $permiso->name }}" 
                                                       id="permiso_{{ $permiso->id }}">
                                                <label class="form-check-label small" for="permiso_{{ $permiso->id }}">
                                                    {{ str_replace('admin.', '', $permiso->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Permisos
                    </button>
                </div>
            </form>
            @else
                <div class="card-body">
                    <div class="alert alert-warning">
                        No tienes permisos para gestionar los permisos de roles.
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .form-check {
        margin-bottom: 0.3rem;
    }
    .form-check-label {
        font-size: 0.85rem;
        cursor: pointer;
    }
    .card-header .card-title {
        font-size: 0.95rem;
    }
    .select-category {
        padding: 2px 6px;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // SINCRONIZACIÓN CON BASE DE DATOS
    const rolePermissions = @json($rol->permissions->pluck('name'));
    const allCheckboxes = $('input[name="permisos[]"]');

    // Sincronizar checkboxes con permisos del rol
    allCheckboxes.each(function() {
        const permissionName = $(this).val();
        $(this).prop('checked', rolePermissions.includes(permissionName));
    });
    
    console.log('Permisos sincronizados correctamente:', rolePermissions);

    // Seleccionar todos los permisos
    $('#selectAll').click(function() {
        allCheckboxes.prop('checked', true);
    });
    
    // Deseleccionar todos los permisos
    $('#deselectAll').click(function() {
        allCheckboxes.prop('checked', false);
    });
    
    // Seleccionar por categoría
    $('.select-category').click(function() {
        var category = $(this).data('category');
        var checkboxes = $('.form-check[data-category="' + category + '"] input[type="checkbox"]');
        var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
    });
});
</script>
@stop