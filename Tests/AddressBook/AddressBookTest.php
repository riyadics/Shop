<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\AddressBook;

use Antvel\Tests\TestCase;
use Antvel\Customer\Models\User;
use Antvel\AddressBook\AddressBook;
use Antvel\AddressBook\Models\Address;

class AddressBookTest extends TestCase
{
   /**
	 * Creates a new testing user in the database.
	 *
	 * @return Antvel\Customer\Models\User
	 */
	protected function user() : User
	{
		return factory(User::class)->create()->first();
	}

	/**
	 * Create a new addressBook instance.
	 *
	 * @return Antvel\AddressBook\AddressBook
	 */
	protected function addressBook() : AddressBook
	{
		return $this->app->make(AddressBook::class);
	}

	public function test_the_given_user_must_has_2_addresses()
    {
    	$user = $this->user();
    	factory(Address::class, 2)->create(['user_id' => $user->id]);
    	$this->assertCount(2, $this->addressBook()->forUser($user));
    }

    public function test_find_address_by_id()
    {
    	$user = $this->user();
    	factory(Address::class)->create(['user_id' => $user->id]);
    	$address = $this->addressBook()->find(1)->first();
    	$this->assertTrue($user->id == $address->user_id);
    }

    public function test_create_a_new_address()
    {
    	$user = $this->user();
    	$address = $this->addressBook();
    	$address = $address->create($this->faker($user->id), $user);
    	$this->assertTrue($user->id == $address->user_id);
    }

    public function test_create_a_new_address_and_set_it_as_default()
    {
    	$user = $this->user();
    	$address = $this->addressBook()->createAndSetToDefault(
    		$this->faker($user->id), $user
    	);
    	$this->assertTrue((bool) $address->default);
    }

    public function test_destroy_an_address_from_the_database()
    {
        $user = $this->user();
        $address = factory(Address::class)->create(['user_id' => $user->id]);
        $this->addressBook()->destroy($address->id);
        $destroyed = Address::where('id', $address->id)->first();
        $this->assertNull($destroyed);
    }

    /**
     * Returns a fake address for a given user.
     *
     * @param  int $user_id
     * @return array
     */
    protected function faker(int $user_id)
    {
		return [
			'user_id' => $user_id,
			'default' => 0,
			'city' => 'Guacara',
			'state' => 'Carabobo',
			'country' => 'Venezuela',
			'zipcode' => '2001',
			'line1' => 'Urb. Augusto Malave Villalba',
			'line2' => 'Conj#2, Piso#6, Apt#6-2, Los Azules',
			'phone' => '+ 1 405 669 00 00',
			'name_contact' => 'Gustavo Ocanto'
		];
    }
}