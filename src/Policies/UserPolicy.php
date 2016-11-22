<?php

namespace Antvel\Policies;

use Antvel\Components\AddressBook\Models;
use Antvel\Components\Customer\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function ownsAddressBook(User $user, Address $address)
    {
        return $user->id === $address->user_id;
    }
}
