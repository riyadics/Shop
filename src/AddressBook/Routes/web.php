<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group([
	'prefix' => 'user',
	'middleware' => ['web', 'roles'],
	'roles' => array_keys(trans('globals.roles')),
	'namespace' => 'Antvel\AddressBook\Http'

	], function ($router) {

		$router->get('address/', 'AddressBookController@index'); //list

		$router->put('address/store', 'AddressBookController@store'); //store

		$router->put('address/{id}', 'AddressBookController@update'); //update

		$router->get('address/create', 'AddressBookController@create');  //create form

		$router->post('address/delete', 'AddressBookController@destroy'); //delete

		$router->get('address/{id}/edit', 'AddressBookController@edit'); //edit form

		$router->post('address/default', 'AddressBookController@setDefault'); //set default
});