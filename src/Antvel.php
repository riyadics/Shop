<?php

namespace Antvel;

use Antvel\Http\RouteRegistrar;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Route;
use Antvel\Policies\Registrar as Policies;

class Antvel
{
    /**
     * The antvel version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

	/**
     * Get a Antvel route registrar.
     *
     * @param  array  $options
     * @return RouteRegistrar
     */
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->all();
        };

        $options = array_merge($options, [
            'namespace' => '\Antvel\Http\Controllers',
        ]);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }

    /**
     * Register the antvel policies.
     *
     * @return void
     */
    public static function policies()
    {
        Container::getInstance()->make(Policies::class)->registrar();
    }
}