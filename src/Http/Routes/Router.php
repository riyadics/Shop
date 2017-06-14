<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Http\Routes;

use Illuminate\Contracts\Routing\Registrar as Router;

class Router
{
    /**
     * The antvel routers.
     *
     * @var array
     */
    protected $routers = [
    	AddressBookRouter::class,
        ProductsRouter::class,
        UsersRouter::class,
        DashboardRouter::class,
        UtilitiesRouter::class,
    ];

    /**
     * Create a new route registrar instance.
     *
     * @param  Router  $router
     *
     * @return void
     */
    public static function make(Router $router)
    {
    	$class = new static;

    	$class->registrar($router);
    }

    /**
     * Register paths for the given router.
     *
     * @param  Router $illuminateRouter
     *
     * @return void
     */
    protected function registrar(Router $illuminateRouter)
    {
    	foreach ($this->routers as $router) {
    		(new $router)->registrar($illuminateRouter);
    	}
    }
}
