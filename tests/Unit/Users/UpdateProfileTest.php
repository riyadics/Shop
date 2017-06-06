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

use Carbon\Carbon;
use Antvel\User\Listeners\UpdateProfile;
use Antvel\User\Events\ProfileWasUpdated;
use Antvel\User\Models\EmailChangePetition;

class UpdateProfileTest extends UsersTestCase
{
	public function test_a_given_user_can_update_his_profile()
	{
		$user = $this->user();

		$event = new ProfileWasUpdated([
			'nickname' => 'gocanto'
		], $user);

		$listener = (new UpdateProfile(
			$this->getRepo(),
			$this->getPetitionRepo()
		))->handle($event);

		$newUser = $this->getRepo()->profile($user->id);

		$this->assertEquals($newUser->email, $user->email);
		$this->assertEquals($newUser->nickname, 'gocanto');
	}

	public function test_a_given_user_might_want_to_change_his_email_address()
	{
		$user = $this->user();

		$event = new ProfileWasUpdated([
			'email' => 'gocanto@antvel.com'
		], $user);

		$listener = (new UpdateProfile(
			$this->getRepo(),
			$this->getPetitionRepo()
		))->handle($event);

		$petition = $this->getPetitionRepo()->findBy([
			'old_email' => $user->email,
			'new_email' => 'gocanto@antvel.com',
			'confirmed' => '0'
		])->first();

		$newUser = $this->getRepo()->profile($user->id);

		$this->assertTrue($petition->expires_at->gt(Carbon::now()));
		$this->assertTrue('gocanto@antvel.com' != $newUser->email);
		$this->assertInstanceOf(EmailChangePetition::class, $petition);
		$this->assertNull($petition->confirmed_at);
	}
}
