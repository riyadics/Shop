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

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Route;
use Antvel\Tests\Stubs\User as UserStub;
use Antvel\Foundation\Support\{ EventsRegistrar, PoliciesRegistrar, RoutesRegistrar };

class Antvel
{
    /**
     * The Antvel Shop version.
     *
     * @var string
     */
    const VERSION = '1.0';

    /**
     * The Laravel container component.
     *
     * @var Container
     */
    protected $container = null;

    /**
     * Creates a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * Returns the applications user model.
     *
     * @return |App\User|UserStub
     */
    public static function user()
    {
        $antvel = new static;

        if ($antvel->isRunning('shop')) {
            return UserStub::class;
        }

        return $antvel->appUserModel();
    }

    /**
     * Checks whether the application is being run in the given package.
     *
     * @param  string $env
     * @return bool
     */
    protected function isRunning(string $env) : bool
    {
        $path = $this->container->make('app')->environmentPath();

        $path = realpath(mb_strtolower($path));

        return strpos($path, $env) !== false;
    }

    /**
     * Returns the application user model.
     *
     * @return Illuminate\Contracts\Auth\Authenticatable
     */
    protected function appUserModel()
    {
        return $this->container->make('config')
            ->get('auth.providers.users.model');
    }

    /**
     * Registers Antvel events and listeners.
     *
     * @return void
     */
    public static function events()
    {
        (new EventsRegistrar)->registrar();
    }

    /**
     * Register the antvel policies.
     *
     * @return void
     */
    public static function policies()
    {
        (new PoliciesRegistrar)->registrar();
    }

    /**
     * Get a Antvel route registrar.
     *
     * @param  array  $options
     * @return void
     */
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->all();
        };

        Route::group($options, function ($router) use ($callback) {
            $callback(new RoutesRegistrar($router));
        });
    }
}
