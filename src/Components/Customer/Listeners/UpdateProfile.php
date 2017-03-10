<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer\Listeners;

use Antvel\Components\Customer\Events\ProfileWasUpdated;
use Antvel\Components\Customer\CustomersRepository as Customers;
use Antvel\Components\Customer\ChangeEmailRepository as ChangeEmail;

class UpdateProfile
{
    /**
     * The customers repository.
     *
     * @var Customers
     */
    protected $customers = null;

    /**
     * The email petitions repository.
     *
     * @var EmailPetitions
     */
    protected $changeEmail = null;

    /**
     * Creates a new instance.
     *
     * @param Customers $customers
     * @param EmailPetitions $changeEmail
     * @return void
     */
    public function __construct(Customers $customers, ChangeEmail $changeEmail)
    {
        $this->customers = $customers;
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
                'old_email' => $event->customer->email,
                'user_id' => $event->customer->id,
                'petition' => $event->request,
            ]);
        }

        $this->customers->update(
            $event->customer, $event->request, $except = ['email']
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
        if ($event->request['email'] != $event->customer->email) {
            return ! $this->changeEmail->hasPetition([
                'new_email' => $event->request['email'],
                'user_id' => $event->customer->id,
            ]);
        }

        return false;
    }
}
