<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Policies;

use Illuminate\Support\Facades\Gate;

class Registrar
{
	/**
     * The policy mappings for Antvel.
     *
     * @var array
     */
	protected $policies = [
		\Antvel\Customer\Models\User::class => \Antvel\Policies\UserPolicy::class,
	];

    /**
     * Register the antvel policies.
     *
     * @return void
     */
    public function registrar()
    {
    	foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}