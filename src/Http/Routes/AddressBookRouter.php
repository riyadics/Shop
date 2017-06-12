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

class AddressBookRouter implements ComponentRouter
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

			'middleware' => ['web', 'auth'],
            'namespace' => '\AddressBook',

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
}
