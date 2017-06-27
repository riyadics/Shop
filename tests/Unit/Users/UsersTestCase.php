<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Unit\Users;

use Antvel\Tests\TestCase;
use Antvel\User\Models\User;

abstract class UsersTestCase extends TestCase
{
	protected function user(array $data = [], int $number = 1)
	{
		return factory(User::class, $number)
			->create($data)
			->first();
	}

	protected function getRepo()
	{
		return $this->app->make(\Antvel\User\UsersRepository::class);
	}

	protected function getPetitionRepo()
	{
		return $this->app->make(\Antvel\User\ChangeEmailRepository::class);
	}
}
