<?php

namespace Antvel;

use Antvel\Http\RouteRegistrar;
use Illuminate\Support\Facades\Route;

class Antvel
{
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
}