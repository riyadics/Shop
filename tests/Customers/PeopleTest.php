<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Customers;

use Antvel\Customer\Models\{ User, Person };

class PeopleTest extends UsersTestCase
{
	public function test_create_a_new_person_in_databse()
	{
		$person = factory(Person::class)->create([
    		'user_id' => $this->user(['role' => 'person'])->id
    	])->first();

		$this->assertEquals($person->user->role, 'person');
    	$this->assertInstanceOf(User::class, $person->user);
	}
}