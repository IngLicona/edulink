<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Directiva para botón ver
        Blade::directive('viewButton', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.show')): ?>";
        });
        Blade::directive('endviewButton', function () {
            return '<?php endif; ?>';
        });

        // Directiva para botón crear
        Blade::directive('createButton', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.create')): ?>";
        });

        Blade::directive('endcreateButton', function () {
            return '<?php endif; ?>';
        });

        // Directiva para botón editar
        Blade::directive('editButton', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.edit')): ?>";
        });

        Blade::directive('endeditButton', function () {
            return '<?php endif; ?>';
        });

        // Directiva para botón eliminar
        Blade::directive('deleteButton', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.delete')): ?>";
        });

        Blade::directive('enddeleteButton', function () {
            return '<?php endif; ?>';
        });

        // Directiva para botón permisos
        Blade::directive('permisosButton', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.permisos')): ?>";
        });

        Blade::directive('endpermisosButton', function () {
            return '<?php endif; ?>';
        });

        // Directiva para modal crear
        Blade::directive('modalCreate', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.create')): ?>";
        });

        Blade::directive('endmodalCreate', function () {
            return '<?php endif; ?>';
        });

        // Directiva para modal editar
        Blade::directive('modalEdit', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.edit')): ?>";
        });

        Blade::directive('endmodalEdit', function () {
            return '<?php endif; ?>';
        });

        // Directiva para mostrar modal en errores
        Blade::directive('showModalOnErrors', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && auth()->user()->can('admin.{$module}.create') && \$errors->any()): ?>";
        });

        Blade::directive('endshowModalOnErrors', function () {
            return '<?php endif; ?>';
        });

        // Directiva para cuando no hay acciones
        Blade::directive('noActions', function ($expression) {
            $params = eval("return $expression;");
            $module = $params['module'] ?? '';
            return "<?php if(auth()->check() && !auth()->user()->can('admin.{$module}.edit') && !auth()->user()->can('admin.{$module}.delete') && !auth()->user()->can('admin.{$module}.permisos')): ?>";
        });

        Blade::directive('endnoActions', function () {
            return '<?php endif; ?>';
        });
    }
}