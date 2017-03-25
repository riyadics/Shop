<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\User;

use Antvel\Tests\TestCase;
use Antvel\Tests\Stubs\User;
use Antvel\User\Models\Person;

abstract class UsersTestCase extends TestCase
{
	protected function user(array $data = [], int $number = 1)
	{
		return factory(User::class, $number)
			->create($data)
			->first();
	}

	protected function person(array $data = [], int $number = 1)
	{
		return factory(Person::class, $number)
			->create($data)
			->first();
	}
}