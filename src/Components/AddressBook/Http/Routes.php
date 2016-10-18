<?php

Route::group([
	'prefix' => 'user',
	'middleware' => ['web', 'roles'],
	'roles' => array_keys(trans('globals.roles')),
	'namespace' => 'Antvel\Components\AddressBook\Http'

	], function ($router) {

		$router->get('address/', 'AddressBookController@index'); //list

		$router->put('address/store', 'AddressBookController@store'); //store

		$router->put('address/{id}', 'AddressBookController@update'); //update

		$router->get('address/create', 'AddressBookController@create');  //create form

		$router->post('address/delete', 'AddressBookController@destroy'); //delete

		$router->get('address/{id}/edit', 'AddressBookController@edit'); //edit form

		$router->post('address/default', 'AddressBookController@setDefault'); //set default
});