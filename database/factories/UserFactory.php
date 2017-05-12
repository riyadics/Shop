<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Antvel\User\Policies\Roles;
use Antvel\User\Models\{ User, Person, Business, EmailChangePetition };

$factory->define(User::class, function (Faker $faker) use ($factory)
{
    return [
        'password' => bcrypt('123456'),
        'nickname' => str_limit($faker->userName, 60),
        'facebook' => str_limit($faker->userName, 100),
        'twitter' => '@' . str_limit($faker->userName, 100),
        'email' => str_limit($faker->unique()->email, 100),
        'role' => array_rand(Roles::allowed(), 1),
        'pic_url' => '/img/pt-default/'.$faker->numberBetween(1, 20).'.jpg',
        'preferences' => '{"product_viewed":"","product_purchased":"","product_shared":"","product_categories":"","my_searches":""}',
    ];
});

$factory->define(Person::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create(['role' => 'person'])->id;
        },
        'last_name' => str_limit($faker->lastName, 60),
        'first_name' => str_limit($faker->firstName, 60),
        'home_phone' => str_limit($faker->e164PhoneNumber, 20),
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->dateTimeBetween('-40 years', '-16 years')
    ];
});

$factory->define(Business::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create(['role' => 'business'])->id;
        },
        'creation_date' => $faker->date(),
        'business_name' => str_limit($faker->company, 60),
        'local_phone' => str_limit($faker->e164PhoneNumber, 20),
    ];
});

$factory->define(EmailChangePetition::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'expires_at' => Carbon::now()->addWeek(),
        'token' => str_limit($faker->unique()->sha1, 60),
        'old_email' => str_limit($faker->email, 60),
        'new_email' => str_limit($faker->email, 60),
        'confirmed_at' => null,
        'confirmed' => '0',
    ];
});
