<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\User\Listeners;

use Antvel\User\Events\ProfileWasUpdated;
use Antvel\User\UsersRepository as Users;
use Antvel\User\ChangeEmailRepository as ChangeEmail;

class UpdateProfile
{
    /**
     * The users repository.
     *
     * @var Users
     */
    protected $users = null;

    /**
     * The email petitions repository.
     *
     * @var EmailPetitions
     */
    protected $changeEmail = null;

    /**
     * Creates a new instance.
     *
     * @param Users $users
     * @param EmailPetitions $changeEmail
     * @return void
     */
    public function __construct(Users $users, ChangeEmail $changeEmail)
    {
        $this->users = $users;
        $this->changeEmail = $changeEmail;
    }

    /**
     * Handle the event.
     *
     * @param  ProfileWasUpdated  $event
     * @return void
     */
    public function handle(ProfileWasUpdated $event)
    {
        //If the user requested a new email address, we create a new email change
        //petition and send a confirmation email.
        if ($sendConfirmationEmail = $this->emailWasChanged($event)) {
            $event->petition = $this->changeEmail->createPetition([
                'old_email' => $event->user->email,
                'user_id' => $event->user->id,
                'petition' => $event->request,
            ]);
        }

        $this->users->update(
            $event->user, $event->request, $except = ['email']
        );

        //We stop the event propagation if the user did not ask for a new email address.
        return $sendConfirmationEmail;
    }

    /**
     * Checks whether the user changed his email address.
     *
     * @param  ProfileWasUpdated $event
     * @return bool
     */
    protected function emailWasChanged($event) : bool
    {
        //If the user requested a new email address, we check whether the requested
        //email address has an active email change petitions.
        if ($event->request['email'] != $event->user->email) {
            return ! $this->changeEmail->hasPetition([
                'new_email' => $event->request['email'],
                'user_id' => $event->user->id,
            ]);
        }

        return false;
    }
}
