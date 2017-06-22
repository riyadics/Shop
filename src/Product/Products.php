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
use Illuminate\Support\Facades\Cache;

class Products extends Repository
{
	/**
	 * The maximum of pictures files per product.
	 */
	const MAX_PICS = 5;

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
    	return Product::create($attributes);
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
	 * @param integer $limit
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function filter($request = [], $limit = null)
	{
		return $this->getModel()
			->with('category')
			->actives()
			->filter($request)
			->orderBy('rate_val', 'desc');
	}

	/**
	 * Generates a suggestion based on a given constraints.
	 *
	 * @param  \Illuminate\Support\Collection $products
	 * @param  int $limit
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function suggestFor($products, $key = 'my_searches', int $limit = 8)
	{
		return Cache::remember('suggestions_for_searched_products', 5, function () use ($products, $key, $limit) {
			return ProductsSuggestions::from($key, $products)
				->take($limit)
				->all();
		});
	}

	/**
	 * Returns a products suggestion based on user's preferences.
	 *
	 * @param mixed $filters
	 * @param int $limit
	 *
	 * @return array
	 */
	public function suggestForPreferences($filters = [], $limit = 4, $preferences = null) : array
	{
		$filters = is_string($filters) ? [$filters] : $filters;

		return ProductsSuggestions::make($filters, $preferences)
			->take($limit)
			->all();
	}
}
