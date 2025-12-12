<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\JavaApiService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar el servicio Java API
        $this->app->singleton(JavaApiService::class, function ($app) {
            return new JavaApiService();
        });
    }

    public function boot()
    {
        //
    }
}