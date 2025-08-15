<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solo forzar HTTPS en producción y cuando la app esté realmente en HTTPS
        if (App::environment('production') && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }
        
        // También forzar HTTPS si la variable está configurada explícitamente
        if (config('app.force_https', false) && !App::environment('local')) {
            URL::forceScheme('https');
        }
    }
}