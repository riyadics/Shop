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

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Antvel\Components\Customer\Events\ProfileWasUpdated;
use Antvel\Components\Customer\Mail\NewEmailConfirmation;

class SendNewEmailConfirmation implements ShouldQueue
{
    /**
     * The Laravel mail component.
     *
     * @var Mailer
     */
    protected $mailer = null;

    /**
     * Create a new event instance.
     *
     * @param array $request
     * @param Authenticatable $customer
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * Handle the event.
     *
     * @param  ProfileWasUpdated  $event
     * @return void
     */
    public function handle(ProfileWasUpdated $event)
    {
        if (! is_null($event->petition)) {
            $this->mailer->send(
                new NewEmailConfirmation($event->petition, $event->customer)
            );
        }
    }
}
