<?php

use Faker\Generator as Faker;
use Antvel\Components\AddressBook\Models\Address;
use Antvel\Components\Customer\Models\{ User, Person, Business };

$factory->define(User::class, function (Faker $faker) use ($factory)
{
    return [
        'password' => bcrypt('123456'),
        'nickname' => $faker->userName,
        'facebook' => $faker->userName,
        'twitter' => '@'.$faker->userName,
        'email' => $faker->unique()->email,
        'role' => array_rand(resolveTrans('globals.roles'), 1),
        'pic_url' => '/img/pt-default/'.$faker->numberBetween(1, 20).'.jpg',
        'preferences' => '{"product_viewed":[],"product_purchased":[],"product_shared":[],"product_categories":[],"my_searches":[]}',
    ];
});

$factory->define(Person::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'last_name' => $faker->lastName,
        'first_name' => $faker->firstName,
        'home_phone' => $faker->e164PhoneNumber,
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->dateTimeBetween('-40 years', '-16 years')
    ];
});

$factory->define(Business::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'creation_date' => $faker->date(),
        'business_name' => $faker->company,
        'local_phone' => $faker->e164PhoneNumber,
    ];
});

$factory->define(Address::class, function (Faker $faker) use ($factory)
{
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'default' => 0,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'zipcode' => $faker->postcode,
        'line1' => $faker->streetAddress,
        'line2' => $faker->streetAddress,
        'phone' => $faker->e164PhoneNumber,
        'name_contact' => $faker->streetName,
    ];
});
