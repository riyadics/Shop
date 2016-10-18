<?php

namespace Antvel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
	/*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mapWebRoutes();
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['Antvel'];
    }

     /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        require __DIR__ . '/Http/Routes.php';
    }
}
