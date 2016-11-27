<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer;

use Antvel\Components\AddressBook\Models\Address;
use Antvel\Components\Customer\Models\{ Person, Bussiness };

trait hasAntvel
{
    /**
     * Returns the user profile.
     */
	public function profile()
    {
        if (in_array($this->role, ['business', 'nonprofit'])) {
            return $this->hasOne(Business::class);
        }

        return $this->hasOne(Person::class);
    }

    /**
     * Returns the user addressBook.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}