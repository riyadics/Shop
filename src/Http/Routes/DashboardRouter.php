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

use Antvel\Contracts\ComponentRouter;
use Illuminate\Contracts\Routing\Registrar;

class DashboardRouter implements ComponentRouter
{
	/**
	 * Register the address book component routes in the given router.
	 *
	 * @param  Registrar $router
	 *
	 * @return void
	 */
	public function registrar(Registrar $router)
	{
		$router->group([

            'middleware' => ['web', 'auth', 'managers'],
            'prefix' => 'dashboard',

        ], function ($router) {

            $router->get('dashboard', 'Categories\CategoriesController@index')->name('dashboard.home');
            $router->get('/', 'Categories\CategoriesController@index')->name('dashboard.home');

            $router->resource('categories', 'Categories\CategoriesController');
            $router->resource('features', 'Product\FeaturesController');
            $router->resource('productsF', 'Product\Products2Controller', ['except' => 'index']);
            $router->get('products', 'Product\Products2Controller@list')->name('products.list');

        });
	}
}
