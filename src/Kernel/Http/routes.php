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

$components = [
	'AddressBook'
];

foreach ($components as $component) {
	require $baseDir . $component . '/routes/web.php';
}