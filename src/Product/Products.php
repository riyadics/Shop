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

use Cache;
use Antvel\Support\Repository;
use Antvel\Product\Models\Product;
use Antvel\Product\Parsers\SuggestionsConstraints;

class Products extends Repository
{
	/**
	 * Creates a new instance.
	 *
	 * @param Product $product
	 */
	public function __construct(Product $product)
	{
		$this->setModel($product);
	}

	/**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
    	//
    }

    /**
     * Update a Model in the database.
     *
     * @param array $attributes
     * @param Category|mixed $idOrModel
     * @param array $options
     *
     * @return bool
     */
    public function update(array $attributes, $idOrModel, array $options = [])
    {
    	//
    }

	/**
	 * Filters products by a given request.
	 *
	 * @param array $request
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function filter($request = [], $limit = 10)
	{
		return $this->getModel()->with('category')
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

		return Cache::remember('suggestions_for_searched_products', 5, function () use ($constraints, $limit) {
			return $this->getModel()->distinct()->whereNotIn('id', $constraints['except'])
				->suggestionsFor($constraints['tags'])
				->orderBy('rate_val', 'desc')
				->take($limit)
				->get();
		});
	}
}
