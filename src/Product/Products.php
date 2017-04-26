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
use Antvel\Product\Parsers\SuggestionsConstraints;

class Products
{
	/**
	 * Filters products by a given request.
	 *
	 * @param  \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function filter($request, $limit = 10)
	{
		return Product::with('category')
			->actives()
			->filter($request)
			->orderBy('rate_val', 'desc')
			->paginate(28);
	}

	/**
	 * Generates a suggestion based on a given constraints.
	 *
	 * @param  \Illuminate\Database\Eloquent\Collection $products
	 * @param  int $limit
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function suggestFor($products, int $limit = 8)
	{
		$constraints = SuggestionsConstraints::complete('searched_products')
				->with($products)
				->all();

		return Product::distinct()->whereNotIn('id', $constraints['except'])
			->suggestionsFor($constraints['tags'])
			->orderBy('rate_val', 'desc')
			->take($limit)
			->get();
	}
}
