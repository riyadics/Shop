<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use Environment, InteractWithPictures;

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadFactories();
        $this->loadMigrations();
    }

    /**
     * Sign in a given user.
     *
     * @param  string $state
     * @param  array  $attr
     *
     * @return void
     */
    protected function signIn($attr = [])
    {
        $user = factory('Antvel\User\Models\User')->create($attr);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Sign in an user as given state.
     *
     * @param  string $state
     * @param  array  $attr
     *
     * @return void
     */
    protected function signInAs($state, $attr = [])
    {
        $user = factory('Antvel\User\Models\User')->states($state)->create($attr);

        $this->actingAs($user);

        return $user;
    }
}
