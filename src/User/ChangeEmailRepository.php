<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\User;

use Carbon\Carbon;
use Antvel\User\Models\EmailChangePetition;

class ChangeEmailRepository
{
    /**
     * Creates a new petition.
     *
     * @param  array $data
     * @return EmailChangePetition
     */
    public function createPetition($data)
    {
        $this->clearPendingPetitions();

        return EmailChangePetition::create([
            'expires_at' => Carbon::now()->addMonth(),
            'new_email' => $data['petition']['email'],
            'old_email' => $data['old_email'],
            'user_id' => $data['user_id'],
            'token' => str_random(60),
        ]);
    }

    /**
     * Checks whether the petition exist by a given constraints.
     *
     * @param  array $data
     * @return boolean
     */
    public function hasPetition($data) : bool
    {
        $petition = $this->findBy([
            'new_email' => $data['new_email'],
            'user_id' => $data['user_id'],
            'confirmed' => '0',
        ]);

        return !! $petition->count() > 0;
    }

    /**
     * Finds petitions by a given constraints.
     *
     * @param  array $constraints
     * @param  integer $take
     * @return EmailChangePetition
     */
    public function findBy($constraints, $take = 1)
    {
        return EmailChangePetition::where($constraints)->take($take)->get();
    }

    /**
     * Delete the pending petitions from the database.
     *
     * @return void
     */
    protected function clearPendingPetitions()
    {
        EmailChangePetition::where('confirmed', 0)->delete();
    }

    /**
     * Confirms the user's new email address.
     *
     * @param  int $user_id
     * @param  string $token
     * @param  string $email
     * @return EmailChangePetition
     */
    public function confirm($user_id, string $token, string $email)
    {
        $petition = $this->findBy([
            ['expires_at', '>=', Carbon::now()],
            'user_id' => $user_id,
            'new_email' => $email,
            'token' => $token,
            'confirmed' => 0,
        ])->first();

        if (! is_null($petition)) {
            $petition->confirmed = 1;
            $petition->confirmed_at = Carbon::now();
            $petition->save();
        }

        return $petition;
    }
}
