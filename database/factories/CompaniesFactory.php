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
use Antvel\Company\Models\Company;

$factory->define(Company::class, function (Faker $faker) use ($factory)
{
    $name = str_replace('-', ' ', $faker->unique()->company);
    $username = str_replace([' ', ','], '', $name);
    $domain = $username . $faker->randomElement(['.com', '.net', '.org']);

    return [
        //Profile information
        'name' => $name = $faker->unique()->company,
        'description' => $faker->text(200),
        'email' => 'info@'.$domain,
        'logo' => '/img/pt-default/'.$faker->unique()->numberBetween(1, 330).'.jpg',
        'slogan' => $faker->catchPhrase,
        'theme' => null,
        'status' => true,

        //Contact information
        'contact_email' => 'contact@' . $domain,
        'sales_email' => 'sales@' . $domain,
        'support_email' => 'support@' . $domain,
        'phone_number' => $faker->e164PhoneNumber,
        'cell_phone' => $faker->e164PhoneNumber,
        'address' => $faker->streetAddress,
        'state' => $faker->state,
        'city' => $faker->city,
        'zip_code' => $faker->postcode,

        //Social information
        'website' => 'http://' . $domain,
        'twitter' => 'https://twitter.com/' . $username,
        'facebook' => 'https://www.facebook.com/' . $username,
        'facebook_app_id' => $faker->md5,
        'google_plus' => 'https://plus.google.com/u/0/+' . $username,
        'google_maps_key_api' => $faker->md5,

        //SEO information
        'keywords' => implode(',', $faker->words(20)),

        //CMS information
        'about_us' => $faker->text(500),
        'refund_policy' => $faker->text(500),
        'privacy_policy' => $faker->text(500),
        'terms_of_service' => $faker->text(500),
    ];
});
