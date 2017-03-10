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
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomersRepository
{
	/**
	 * The application user model.
	 *
	 * @var Authenticatable
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
     * @return Authenticatable
     */
    public function user()
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
     * @return null|Authenticatable
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
     * @return null|Authenticatable
     */
    public function profile($user_id = null)
    {
        //if user id was not provided, we assumed the update will be
        //done on the logged user.
        if (is_null($user_id)) {
            return $this->user();
        }

        //we retrieve the user for the given id.
        return $this->find($user_id, 'profile');
    }

    /**
     * Updates the user information with a given data.
     *
     * @param  int|Authenticatable $user
     * @param  array $data
     * @param  array $except
     * @return void
     */
    public function update($user, array $data, array $except = [])
    {
        if (is_int($user)) {
            $user = $this->profile($user);
        }

        $data = Collection::make($data)
            ->except($except);

        //Update the user information with the given data.
        if ($data->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->fill($data->all())->save();

        // //Update the user profile information with the given data.
        $user->profile->fill($data->all())->save();
    }
}
