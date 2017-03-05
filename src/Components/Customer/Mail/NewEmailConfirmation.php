<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\Authenticatable;

class NewEmailConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The registered user.
     *
     * @var Authenticatable
     */
    protected $user = null;
    protected $request = null;

    /**
     * The email template.
     *
     * @var array
     */
    protected $template = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $template = [])
    {
        $this->user = $event->customer;
        $this->request = $event->request;
        $this->template = $this->parseTemplate($template);
    }

    /**
     * Parses the email information.
     *
     * @param  array  $template
     * @return array
     */
    protected function parseTemplate(array $template = []) : array
    {
        return [
            'subject' => isset($template['subject']) ? $template['subject'] : 'New Email Confirmation',
            'view' => isset($template['view']) ? $template['view'] : 'emails.newEmailConfirmation'
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	 return $this->subject($this->template['subject'])
            ->to($this->user->email)
            ->view($this->template['view'], [
                'name' => $this->user->fullName,
                'route' => $this->route(),
        ]);
    }

    /**
     * Returns the confirmation url.
     *
     * @return string
     */
    protected function route() : string
    {
        return route('customer.newemail', [
            'token' => $this->user->confirmation_token,
            'email' => $this->user->email
        ]);
    }
}
