<?php

namespace Antvel;

use Antvel\Http\RouteRegistrar;
use Illuminate\Auth\Authenticatable;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Route;
use Antvel\Policies\Registrar as Policies;
use Illuminate\Contracts\Config\Repository as Config;

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

    /**
     * Checks whether the app user model is valid.
     *
     * @return bool
     */
    public static function doesntHaveUserModel()
    {
        $model = static::userModel();
// dd('----->>>', new $model);
// dd('----->>>', new $model, (get_class($model) instanceof Authenticatable));
        if (is_null($model) || ! class_exists($model)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the applications user model.
     *
     * @return null|App\User
     */
    protected static function userModel()
    {
        $config = Container::getInstance()->make(Config::class);

        return $config->get('auth.providers.users.model');
    }
}