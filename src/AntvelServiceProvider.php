<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AntvelServiceProvider extends ServiceProvider
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
        $this->publishSeeders();
        $this->loadMigrations();
        $this->loadTranslations();
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
        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/Kernel/Http/routes.php';
        }
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/Kernel/Database/Migrations'
        );
    }

    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(
            __DIR__ . '/../resources/lang',
        'antvel');
    }

    /**
     * Publish the antvel seeders in the local seeds app folder.
     *
     * @return void
     */
    protected function publishSeeders()
    {
        $this->publishes([
            __DIR__ . '/Kernel/Database/Seeds' => database_path('seeds')
        ], 'seeds');
    }
}
