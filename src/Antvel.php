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

use Antvel\Http\RouteRegistrar;
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
     * Controls whether tests are running.
     *
     * @var boolean
     */
    protected static $testsAreRunning = false;

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
    public static function userModel()
    {
        if (static::$testsAreRunning) {
            //If phpunit is running, we retrieve the user model stub
            //for testing purposes.
            return \Antvel\Tests\Stubs\User::class;
            // return \Antvel\Tests\Stubs\UserModelStub::class;
        }

        $config = Container::getInstance()->make(Config::class);

        return $config->get('auth.providers.users.model');
    }

    /**
     * Tells the application that tests are about to start.
     *
     * @return void
     */
    public static function beginsTests()
    {
        //Allows the application knows whether phpunit is running,
        //so we can swap the user models by the testing one.
        static::$testsAreRunning = true;
    }
}