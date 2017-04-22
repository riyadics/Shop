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


class FiltersParser
{
	/**
	 * Parses the given collection.
	 *
	 * @param \Illuminate/Database/Eloquent/Collection $products
	 *
	 * @return array
	 */
	public static function parse($products) : array
	{
		$parser = new static;

		$features = $parser->features(
			$products->pluck('features')
		);

		return array_merge($features, [
			'conditions' => array_count_values($products->pluck('condition')->all()),
			'brands' => array_count_values($products->pluck('brand')->all())
		]);
	}

	/**
	 * Returns the mapped features with their quantities.
	 *
	 * @param  array $features
	 *
	 * @return array
	 */
	protected function features($features) : array
	{
		$filters = [];

		$features = $this->mapFeatures($features);

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
            $feature = collect(json_decode($feature))->except(['images', 'dimensions', 'weight', 'brand']);
            foreach ($feature as $key => $value) {
                $map[$key][] = $value;
            }
        }

        return $map;
	}
}
