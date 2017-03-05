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

class UpdateProfile
{
    /**
     * Handle the event.
     *
     * @param  ProfileWasUpdated  $event
     * @return void
     */
    public function handle(ProfileWasUpdated $event)
    {
        //WORKING NOTES
        //If the user requested a new email, we have to send a confirmation to his mailbox and
        //update his email address JUST when the click on the sent link.


        if ($continue = $this->wantsDifferentEmailEddress($event)) {
            $event->request['confirmation_token'] = str_random(60);
        }

        //We update the user information with the given request and save the changes.
        $event->customer->fill($event->request);
        $event->customer->save();

        //We update the user profile information with the given request and save the changes.
        $event->customer->profile->fill($event->request);
        $event->customer->profile->save();

        if (! $continue) {
            return false;
        }
    }

    /**
     * Checks whether the user changed his email address.
     *
     * @param  ProfileWasUpdated $event
     * @return bool
     */
    protected function wantsDifferentEmailEddress(ProfileWasUpdated $event) : bool
    {
        return $event->request['email'] != $event->customer->email;
    }
}
