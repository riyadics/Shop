<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$baseDir = __DIR__ . '/../../';

$routesMap = [
	'AddressBook'
];

foreach ($routesMap as $route) {
	require $baseDir . $route . '/routes/web.php';
}