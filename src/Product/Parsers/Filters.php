<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Parsers;

use Cache;
use Antvel\Product\Features;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;

class Filters
{
	/**
	 * The allowed features to be in products listing.
	 *
	 * @var array
	 */
	protected $allowed = [];

	/**
	 * The products list under evaluation.
	 *
	 * @var \Illuminate/Database/Eloquent/Collection
	 */
	protected $products = null;

	/**
	 * Cretaes a new instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Collection
	 *
	 * @return void
	 */
	public function __construct($products)
	{
		$this->products = $products;

		$this->allowed = $this->allowed();
	}

	/**
	 * Returns the allowed features to be in products listing.
	 *
	 * @return array
	 */
	protected function allowed() : array
	{
		$cacheExpiration = 43800; //one month

		return Cache::remember('product_features_filterable', $cacheExpiration, function () {
			return Container::getInstance()->make(Features::class)
				->filterable()
				->all();
		});
	}

	/**
	 * Parses the given collection.
	 *
	 * @param \Illuminate\Database\Eloquent\Collection $products
	 *
	 * @return array
	 */
	public static function parse($products) : array
	{
		$parser = new static ($products);

		return $parser->all();
	}

	/**
	 * Returns the parsed filters.
	 *
	 * @return array
	 */
	protected function all() : array
	{
		return array_merge([
			'category' => $this->forCategories(),
			'brands' => array_count_values($this->products->pluck('brand')->all()),
			'conditions' => array_count_values($this->products->pluck('condition')->all()),
		], $this->forFeatures());
	}

	/**
	 * Returns the mapped features with their quantities.
	 *
	 * @return array
	 */
	protected function forFeatures() : array
	{
		$filters = [];

		$features = $this->mapFeatures(
			$this->products->pluck('features')
		);

		foreach ($features as $key => $value) {
        	foreach ($features[$key] as $row) {
				if (is_string($row)) {
                    $filters[$key][$row] = isset($filters[$key][$row]) ? $filters[$key][$row] + 1 : 1;
                }
            }
        }

        return $filters;
	}

	/**
	 * Returns a map with the given features.
	 *
	 * @param Illuminate\Support\Collection $features
	 *
	 * @return array
	 */
	protected function mapFeatures($features) : array
	{
		$map = [];

		foreach ($features as $feature) {

			$feature = Collection::make($feature)->only($this->allowed);

            foreach ($feature as $key => $value) {
                $map[$key][] = $value;
            }
        }

        return $map;
	}

	/**
	 * Parses the category filter.
	 *
	 * @return array
	 */
	protected function forCategories() : array
	{
		$counting = $this->categoriesCountValues();

		return $counting->mapWithKeys( function($item, $key) use ($counting) {

			return [
				$key => [
					'id' => $key,
					'name' => $this->categoryNameFor($key),
					'qty' => $item
				]
			];
		})->all();
	}

	/**
	 * Map the given categories with the total of products associated with it.
	 *
	 * @return Collection
	 */
	protected function categoriesCountValues() : Collection
	{
		$counting = array_count_values(
			$this->products->pluck('category_id')->all()
		);

		return Collection::make($counting)->sort();
	}

	/**
	 * Returns the category name for the given key.
	 *
	 * @param  integer $key
	 *
	 * @return string
	 */
	protected function categoryNameFor($key) : string
	{
		return $this->products->pluck('category')
			->where('id', $key)
			->pluck('name')
			->first();
	}
}
