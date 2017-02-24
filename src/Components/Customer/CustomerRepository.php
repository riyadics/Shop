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
use Illuminate\Auth\Authenticatable;

class CustomerRepository
{
	/**
	 * The application user model.
	 *
	 * @var Authenticatable
	 */
	protected $userModel = null;

	/**
	 * Creates a new instance.
	 *
	 * @return void
	 */
	public function __construct()
    {
    	$this->userModel = Antvel::userModel();
    }

    /**
     * Returns the logged user.
     *
     * @return Authenticatable
     */
    protected function user()
    {
    	return auth()->user();
    }

    /**
     * Finds a given user in the database.
     *
     * @param  int $id
     * @return null|Authenticatable
     */
	public function find($id = null)
	{
		$id = is_null($id) ? $this->user()->id : $id;

		abort_if(
            ! $user = $this->userModel::with('profile')->where('id', $id)->first(), 500
        );

		return $user;
	}
}