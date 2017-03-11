<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\User;

use Illuminate\Http\Request;
use Illuminate\Events\Dispatcher;
use Antvel\Foundation\Http\Controller;
use Antvel\User\Requests\ProfileRequest;
use Antvel\User\Events\ProfileWasUpdated;

class UsersController extends Controller
{
    /**
     * The user repository.
     *
     * @var UsersRepository
     */
    protected $user = null;

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
     * @param UsersRepository $user
     * @param Dispatcher $event
     * @return void
     */
	public function __construct(UsersRepository $user, Dispatcher $event)
    {
        $this->user = $user;
        $this->event = $event;
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
            'user' => $this->user->profile(),
            'panel' => $this->view_panel
        ]);
    }

    /**
     * Updates the user profile.
     *
     * @param  ProfileRequest $request
     * @param  int $user
     * @return void
     */
    public function update(ProfileRequest $request, $user = null)
    {
        $user = $this->user->profile($user);

        $this->event->fire(
            new ProfileWasUpdated($request->all(), $user)
        );

        return back();
    }

}
