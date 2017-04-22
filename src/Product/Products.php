<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product;

use Antvel\Contracts\Repository;
use Antvel\Product\Models\Product;

class Products
{
	public function __construct()
	{
		//
	}

	public function filter($request)
	{
		return Product::filter($request)
			->orderBy('rate_val', 'desc')
			->paginate(28);
	}
}
