<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Foundation\Support;

use Illuminate\Contracts\Routing\Registrar as Router;

class RoutesRegistrar
{
	/**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * The base namespace.
     *
     * @var string
     */
    protected $namespace = 'Antvel';

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
        $this->forUser();
        $this->forBackOffice();
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

			// 'prefix' => 'user',
            'middleware' => ['web', 'auth'],
            'namespace' => $this->namespace . '\AddressBook',

		], function ($router) {

			$router->get('addressBook/', 'AddressBookController@index')->name('addressBook.index');
			$router->put('addressBook/store', 'AddressBookController@store')->name('addressBook.store');
			$router->put('addressBook/{id}', 'AddressBookController@update')->name('addressBook.update');
            $router->get('addressBook/{id}/edit', 'AddressBookController@edit')->name('addressBook.edit');
			$router->get('addressBook/create', 'AddressBookController@create')->name('addressBook.create');
            $router->post('addressBook/delete', 'AddressBookController@destroy')->name('addressBook.delete');
			$router->post('addressBook/default', 'AddressBookController@setDefault')->name('addressBook.default');

        });
    }

    /**
     * Registers routes for the customer component.
     *
     * @return void
     */
    protected function forUser()
    {
        $this->router->group([

            'middleware' => ['web', 'auth'],
            'namespace' => $this->namespace . '\User',

        ], function ($router) {

            $router->resource('user', 'UsersController');

            $this->router->group([

                'middleware' => ['web', 'auth'],
                'prefix' => 'user/security'

            ], function ($router) {

                $router->get('confirmEmail/{token}/{email}', 'SecurityController@confirmEmail')->name('user.email');
                $router->patch('{action}/{user?}', 'SecurityController@update')->name('user.action');

            });

        });
    }

    public function forBackOffice()
    {
        $this->router->group([

            'namespace' => $this->namespace . '\WorkShop',
            'middleware' => ['web', 'auth'],
            'prefix' => 'workshop',

        ], function ($router) {

            $router->get('/', 'DashBoardController@index')->name('workshop.home');
            $router->get('dashboard', 'DashBoardController@index')->name('workshop.dashboard');

        });
    }
}
