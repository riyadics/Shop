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
use Antvel\User\Preferences;
use Antvel\Support\Repository;
use Antvel\Product\Models\Product;
use Illuminate\Support\Collection;
use Antvel\User\UsersRepository as Users;

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
	 * @param integer $limit
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function filter($request = [], $limit = null)
	{
		$products = $this->getModel()->with('category')
			->actives()->filter($request)
			->orderBy('rate_val', 'desc')
			->take($limit)
			->get();

		Users::updatePreferences('my_searches', $products);

		return $products;
	}

	/**
	 * Filters products by a given request for the logged in user.
	 *
	 * @param  array  $request
	 * @param  int $limit
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function userProducts($request = [], $limit = null)
	{
		return $this->getModel()->with('category')
			->when(count($request) == 0, function ($query) {
				return $query->actives();
			})
			->filter($request)
			// ->where('user_id', auth()->user()->id)
			->orderBy('rate_val', 'desc')
			->take($limit)
			->paginate(12);
	}

	/**
	 * Generates a suggestion based on a given constraints.
	 *
	 * @param  Collection $products
	 * @param  int $limit
	 *
	 * @return Collection
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
