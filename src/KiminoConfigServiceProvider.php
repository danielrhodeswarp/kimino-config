<?php

namespace Danielrhodeswarp\KiminoConfig;

use Illuminate\Support\ServiceProvider;

class KiminoConfigServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //the routes
        require __DIR__ . '/Http/routes.php';

        //the views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'kimino-config');

        //make views publishable
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/kimino-config'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}