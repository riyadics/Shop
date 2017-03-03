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

use Illuminate\Http\Request;
use Antvel\Components\Customer\Requests\ProfileRequest;

class CustomersController
{
    /**
     * The customer repository.
     *
     * @var CustomerRepository
     */
	protected $customer = null;

    /**
     * The view panel layout. (TEMP while refactoring)
     *
     * @var array
     */
    private $view_panel = [
        'left'   => ['width' => '2', 'class' => 'user-panel'],
        'center' => ['width' => '10'],
    ];

    /**
     * Creates a new instance.
     *
     * @param CustomersRepository $customer
     * @return void
     */
	public function __construct(CustomersRepository $customer)
    {
    	$this->customer = $customer;
    }

    /**
     * Shows the user profile.
     *
     * @return void
     */
	public function index()
	{
        return view('user.profile', [
        	'user' => $this->customer->profile(),
        	'panel' => $this->view_panel
        ]);
	}

    /**
     * Updates the user profile.
     *
     * @param  ProfileRequest $request
     * @param  int $id
     * @return void
     */
    public function update(ProfileRequest $request, $id)
    {
        $this->customer->update(
            $request->all(), $id
        );

        return back();
    }
}