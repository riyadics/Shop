<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Faker\Generator as Faker;
use Antvel\Product\Models\ProductFeatures;

$factory->define(ProductFeatures::class, function (Faker $faker) use ($factory)
{
    return [
        'product_type'  => 'item',
        'validation_rules' => null,
        'name' => 'Feature Name',
        'input_type' => 'text',
        'help_message' => null,
        'filterable' => false,
        'status' => true,
    ];
});
