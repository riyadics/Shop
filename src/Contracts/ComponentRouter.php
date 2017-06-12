<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Contracts;

use Illuminate\Contracts\Routing\Registrar;

interface ComponentRouter
{
	/**
	 * Register the address book component routes in the given router.
	 *
	 * @param Router $router
	 *
	 * @return void
	 */
	public function registrar(Registrar $router);
}
