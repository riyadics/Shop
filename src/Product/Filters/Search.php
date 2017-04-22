<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Filters;

use Illuminate\Database\Eloquent\Builder;

class Search
{
	/**
	 * The requested seed.
	 *
	 * @var int
	 */
	protected $seed = null;

	protected $searchable = ['name', 'description', 'features', 'brand', 'tags'];

	/**
     * Create a new instance.
     *
     * @param string $seed
     *
     * @return void
     */
	public function __construct(string $seed, Builder $builder)
	{
		$this->seed = $seed;
		$this->builder = $builder;
	}

	/**
	 * Builds the query with the given category.
	 *
	 * @return Builder
	 */
	public function query() : Builder
	{
		if (trim($seed = $this->seed) !== '') {
			$this->builder->where(function ($query) use ($seed) {
				// foreach ($this->searchable as $field) {
				// 	$query->orWhere($field, 'like', '%'.$seed.'%');
				// }
				// return $query;
				return $this->resolveQuery($query, $seed);
			});
		}

		return $this->builder;
	}

	protected function resolveQuery(Builder $builder, string $seed)
	{
		foreach ($this->searchable as $field) {
			$builder->orWhere($field, 'like', '%'.$seed.'%');
		}

		return $builder;
	}
}
