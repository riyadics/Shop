<?php

namespace Antvel\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Antvel\Components\AddressBook\Models\Address;
use Antvel\Components\Customer\Models\{ Business, Person };

class UserModelStub extends Model implements Authenticatable
{
	protected $table = 'users';

	public function profile()
    {
        // if (in_array($this->role, ['business', 'nonprofit'])) {
        //     return $this->hasOne(Business::class);
        // }

        return $this->hasOne(Person::class);
    }

	public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}