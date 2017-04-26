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

use Illuminate\Support\Collection;

class SuggestionsConstraints
{
	/**
	 * The data to be removed from the analyzing collection.
	 *
	 * @var array
	 */
	protected $except = [];

	/**
	 * The collection to be analyzed.
	 *
	 * @var null
	 */
	protected $items = null;

	/**
	 * Completes the products results for a given key.
	 *
	 * @param string $scope
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function complete(string $scope)
	{
		$suggestions = new static ($scope);

		return $suggestions;
	}

	/**
	 * Assigns the given collection.
	 *
	 * @param array $except
	 *
	 * @return self
	 */
	public function with($items)
	{
		$this->items = $items;

		$this->except = $items->pluck('id')->all();

		return $this;
	}

	/**
	 * Returns the suggestions.
	 *
	 * @return array
	 */
	public function all() : array
	{
		if ($this->tags()->isEmpty()) {
			return [];
		}

		return [
			'tags' => $this->tags()->all(),
			'except' => $this->except,
		];
	}

	/**
	 * Returns the tags related to the given collection.
	 *
	 * @return Collection
	 */
	protected function tags() : Collection
	{
		return $this->items->pluck('tags')->flatMap(function ($item, $key) {
			return Collection::make(explode(',', trim($item,'"')));
		})->unique();
	}
}
