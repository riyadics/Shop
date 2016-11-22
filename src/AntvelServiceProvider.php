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
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadTranslationsFrom(
            realpath(__DIR__ . '/../resources/lang')
        , 'antvel');

        if ($this->app->runningInConsole()) {

            $this->loadMigrationsFrom(
                __DIR__ . '/../database/migrations'
            );

            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/antvel'),
            ], 'antvel-trans');

             $this->publishes([
                __DIR__ . '/../database/seeds' => database_path('seeds')
            ], 'antvel-seeds');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
