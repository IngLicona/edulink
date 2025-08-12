<?php

return [
    'providers' => [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        // ... otros providers
        
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\BladeServiceProvider::class,  // Agregamos nuestro BladeServiceProvider
    ],
];
