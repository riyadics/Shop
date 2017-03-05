<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class ProfileWasUpdated
{
    use SerializesModels;

    /**
     * The laravel request component.
     *
     * @var Request
     */
    public $request = null;

    /**
     * The antvel customer component.
     *
     * @var Authenticatable
     */
    public $customer = null;

    /**
     * Create a new event instance.
     *
     * @param array $request
     * @param Authenticatable $customer
     * @return void
     */
    public function __construct(array $request, Authenticatable $customer)
    {
        $this->request = $request;
        $this->customer = $customer;
    }
}