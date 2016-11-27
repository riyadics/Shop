<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Http;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
	/**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for Antvel.
     *
     * @return void
     */
    public function all()
    {
        $this->forAddressBook();
    }

    /**
     * Register routes for the User AddressBook.
     *
     * @return void
     */
    protected function forAddressBook()
    {
        $this->router->group([

			'prefix' => 'user',
            'middleware' => ['web', 'auth'],
            'namespace' => 'Antvel\Components\AddressBook',

		], function ($router) {

			$router->get('address/', 'AddressBookController@index');
			$router->put('address/store', 'AddressBookController@store');
			$router->put('address/{id}', 'AddressBookController@update');
			$router->get('address/create', 'AddressBookController@create');
			$router->get('address/{id}/edit', 'AddressBookController@edit');
            $router->post('address/delete', 'AddressBookController@destroy');
			$router->post('address/default', 'AddressBookController@setDefault');

        });
    }
}