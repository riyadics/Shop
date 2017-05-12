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

use Antvel\User\Preferences;
use Antvel\Product\Models\Product;
use Illuminate\Support\Collection;
// use Illuminate\Container\Container;

class ProductsSuggestions
{
	/**
	 * The total records to be retrieved.
	 *
	 * @var int
	 */
	protected $limit = 4;

	/**
	 * The requored suggestions.
	 *
	 * @var Collection
	 */
	protected $constraints = null;

	/**
	 * The array on charge to keep track of the selected products.
	 *
	 * @var array
	 */
	protected $except = [];

	/**
     * The laravel auth component.
     *
     * @var Authenticatable
     */
    protected $auth = null;

	/**
	 * Creates a new instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->except = new Collection;
		// $this->auth = Container::getInstance()->make('auth');
	}

	/**
	 * Creates a new instance for the given constraints.
	 *
	 * @param  array  $constraints
	 *
	 * @return self
	 */
	public static function make(array $constraints = [], $preferences = null)
	{
		$suggestions = new static;

		// $preferences = $suggestions->userPreferences();

		$suggestions->constraints = Preferences::parse($preferences)->all($constraints);

		// dd('in', $suggestions->constraints);

		return $suggestions;
	}

	/**
	 * Returns the user's preferences.
	 *
	 * @return mixed
	 */
	// protected function userPreferences()
	// {
	// 	return $this->auth->check() ? $this->auth->user()->preferences : [];
	// }

	/**
	 * Returns constraints based upon the given items.
	 *
	 * @param  string $key
	 * @param  Collection $items
	 *
	 * @return self
	 */
	public static function from(string $key, Collection $items)
	{
		$suggestions = new static;

		$suggestions->constraints[$key] = $items->map(function ($item, $key) {
			$tags = str_replace('"', '', $item->tags);
			$bucket[] = explode(',', $tags);
			return $bucket;
		})->flatten()->unique()->all();

		return $suggestions;
	}

	/**
	 * Sets the number of records to be retrieved.
	 *
	 * @param  int $limit
	 *
	 * @return self
	 */
	public function take(int $limit = 4)
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Returns the required suggestions.
	 *
	 * @return array
	 */
	public function all() : array
	{
		$products = [];

		foreach ($this->constraints as $key => $filter) {

			//We query the database for the given constraints and
			//save the map the result with the given key.
			$products[$key] = $this->products($key, $filter);

			//We keep track of the resulting products ID in order to
			//avoid repeated products in the listing.
			$this->except = $this->except->merge($products[$key]->pluck('id'));

			//If the query result for the given key did not return enough records to
			//fulfill the request limit, we complete the records with a random
			//query ordered by the product rate value.
			if ($products[$key]->count() < $this->limit) {

				//We merge the returned products for the given key with
				//random records to fulfill the request limit.
				$products[$key] = $this->completeWithRandomProducts(
					$products[$key]
				);
			}
		}

		return $products;
	}

	/**
	 * Returns a products collection for the given type and constraints.
	 *
	 * @param  string $type
	 * @param  array $constraints
	 *
	 * @return Collection
	 */
	protected function products(string $type, array $constraints) : Collection
	{
		return Product::when( $this->except->count() > 0 , function($query) {
				return $query->whereNotIn('id', $this->except->all());
			})
			->suggestionsFor($type, $constraints)
			->orderBy('rate_val', 'desc')
			->take($this->limit)
			->get();
	}

	/**
	 * Completes the given collection with random values.
	 *
	 * @param  Collection $products
	 *
	 * @return Collection
	 */
	protected function completeWithRandomProducts(Collection $products) : Collection
	{
		$currentLimit = $this->limit - $products->count();

		$inRandomOrder = Product::when( $this->except->count() > 0 , function($query) {
				return $query->whereNotIn('id', $this->except->all());
			})
			->orderBy('rate_val', 'desc')
			->take($currentLimit)
			->get();

		return Collection::make($products)->merge($inRandomOrder);
	}
}
