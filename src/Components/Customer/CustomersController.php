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
use Illuminate\Events\Dispatcher;
use Antvel\Components\Customer\Requests\ProfileRequest;
use Antvel\Components\Customer\Events\ProfileWasUpdated;

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
     * The laravel Dispatcher component.
     *
     * @var Dispatcher
     */
    protected $event = null;

    /**
     * Creates a new instance.
     *
     * @param CustomersRepository $customer
     * @param Dispatcher $event
     * @return void
     */
	public function __construct(CustomersRepository $customer, Dispatcher $event)
    {
        $this->event = $event;
        $this->customer = $customer;
    }

    /**
     * Shows the user profile.
     *
     * @return void
     */
	public function index()
	{
        return $this->show();
	}

    /**
     * Shows the user profile.
     *
     * @return void
     */
    public function show()
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
     * @param  int $customer
     * @return void
     */
    public function update(ProfileRequest $request, $customer = null)
    {
        $customer = $this->customer->profile($customer);

        $this->event->fire(
            new ProfileWasUpdated($request->all(), $customer)
        );

        return back();
    }

    /**
     * Confirms the user's new email address.
     *
     * @param  string $token
     * @param  string $email
     * @return void
     */
    public function confirmNewEmailAddress(string $token, string $email)
    {
        $petition = (new ChangeEmailRepository())->confirm(
            $user_id = $this->customer->id, $token, $email
        );

        if (! is_null($petition)) {
            $this->customer->update(
                $user_id, ['email' => $email]
            );
        }

        return redirect()->route('customer.index');
    }
}
