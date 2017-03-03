<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer;

use Antvel\Antvel;
use Illuminate\Container\Container;

class CustomersRepository
{
	/**
	 * The application user model.
	 *
	 * @var Illuminate\Auth\Authenticatable
	 */
	protected $model = null;

    /**
     * The laravel auth component.
     *
     * @var Container
     */
    protected $auth = null;

	/**
	 * Creates a new instance.
	 *
	 * @return void
	 */
	public function __construct(Container $container)
    {
        $model = Antvel::userModel();

        $this->model = new $model;
        $this->auth = $container->make('auth');
    }

    /**
     * Gets a field in the user model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->user()->$key ?? null;
    }

    /**
     * Returns the logged user.
     *
     * @return Illuminate\Auth\Authenticatable
     */
    protected function user()
    {
        return $this->auth->user();
    }

    /**
     * Checks whether the user is logged in.
     *
     * @return boolean
     */
    public function isLoggedIn() : bool
    {
        return !! $this->auth->check();
    }

    /**
     * Finds a given user in the database.
     *
     * @param int $user_id
     * @param array $loaders
     * @return null|Illuminate\Auth\Authenticatable
     */
	public function find($user_id = null, ...$loaders)
	{
        //We fetch the user using either the given id. If the id was not given,
        //we use the one in session..
        $user = $this->model->where('id', $user_id ?? $this->id)->first();

        //We throw an exception if the user was not found, so we avoid the fact
        //that somebody tries to look for a non-existent user..
        abort_if( ! $user, 404);

        //If there was any requested loader, we will try to lazy load the relationship
        //related to it within retrieved query.
        if (count($loaders) > 0) {
            $user->load(implode(',', $loaders));
        }

        return $user;
	}

    /**
     * Returns the user profile.
     *
     * @param  int $user_id
     * @return null|Illuminate\Auth\Authenticatable
     */
    public function profile($user_id = null)
    {
        return $this->find($user_id, 'profile');
    }

    /**
     * Updates the user profile.
     * @param  array  $data
     * @param  int $user_id
     * @return void
     */
    public function update(array $data, $user_id)
    {
        $user = $this->user();

        //we update the user information.
        $user->fill($data);
        $user->save();

        //we update the user profile.
        $user->profile->fill($data);
        $user->profile->save();
    }
}