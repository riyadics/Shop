<?php

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