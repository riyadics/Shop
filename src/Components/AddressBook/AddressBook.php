<?php

namespace Antvel\Components\AddressBook;

use App\User;
use Illuminate\Support\Collection;
use Antvel\Components\AddressBook\Models\Address;

class AddressBook
{
	/**
	 * Return the current user.
	 *
	 * @return User
	 */
	protected function user() : User
	{
		return auth()->user();
	}

	/**
	 * Retrieve the address book for a given user.
	 *
	 * @param  User|null $user
	 * @param  string $sort
	 * @return Collection
	 */
	public function forUser(User $user = null, string $sort = 'default') : Collection
	{
		$user = is_null($user) ? $this->user() : $user;

		return $user->addresses->sortByDesc($sort);
	}

	/**
	 * Retrieve a requested address.
	 *
	 * @param int $id
	 * @return Address
	 */
	public function find(int $id) : Address
	{
		return Address::find($id);
	}

	/**
	 * Create an address in the database.
	 *
	 * @param  array $data
	 * @return Address
	 */
	public function create(array $data) : Address
	{
		$data = array_merge([
			'user_id' => $this->user()->id
		], $data);

		return Address::create($data);
	}

	/**
	 * Crate a new address in the database and set it to default.
	 *
	 * @param  array $data
	 * @return Address
	 */
	public function createAndSetToDefault(array $data) : Address
	{
		$address = $this->create($data);
		$this->setDefault($address);

		return $address;
	}

	/**
	 * Set to default a given address.
	 *
	 * @param Address\int $address
	 * @return void
	 */
	public function setDefault($address)
	{
		$this->resetDefault();

		if (is_integer($address)) {
			$address = $this->find($address);
		}

		$address->default = 1;
		$address->save();
	}

	/**
	 * Reset the default address.
	 *
	 * @return void
	 */
	protected function resetDefault()
	{
		Address::where('user_id', $this->user()->id)
			->where('default', 1)
			->update(['default' => 0]);
	}

	/**
	 * Destroy a given address.
	 *
	 * @param int $id [description]
	 * @return void
	 */
	public function destroy(int$id)
	{
		Address::destroy($id);
	}
}