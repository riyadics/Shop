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

use Antvel\Tests\TestCase;
use Antvel\Tests\Stubs\User;

abstract class UsersTestCase extends TestCase
{
	protected function user(array $data = [], bool $create = true, int $number = 1)
	{
		if ($create) {
			return $this->createUser($data, $number);
		}

		return factory(User::class)->make($data)->first();
	}

	protected function createUser(array $data = [], int $number)
	{
		return factory(User::class, $number)->create($data)->first();
	}

}