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

use Cache;
use Illuminate\Support\Arr;
use Antvel\Categories\Categories;
use Illuminate\Database\Eloquent\Builder;

use Antvel\Categories\Models\Category;

class Category
{
	/**
	 * The categories repository.
	 *
	 * @var Categories
	 */
	protected $categories = null;

	/**
	 * The requested category ID.
	 *
	 * @var int
	 */
	protected $category_id = null;

	/**
	 * The requested category name.
	 *
	 * @var string
	 */
	protected $category_name = null;

	/**
     * Create a new instance.
     *
     * @param string $category
     *
     * @return void
     */
	public function __construct(string $category)
	{
		$this->categories = new Categories;
		$this->parseCategory($category);
	}

	/**
	 * Parses the given category info.
	 *
	 * @param  string $category
	 *
	 * @return void
	 */
	protected function parseCategory($category)
	{
		$category = explode('|', urldecode($category));

		$this->category_name = Arr::last($category);
		$this->category_id = Arr::first($category);
	}

	/**
	 * Builds the query with the given category.
	 *
	 * @param  Builder $query
	 *
	 * @return Builder
	 */
	public function query(Builder $query)
	{
		if (is_null($this->category_id)) {
			return;
		}

		if (count($children = $this->children()) > 0) {
			return $query->whereIn(
				'category_id', $children
			);
		}

		return $query;

	}

	/**
	 * Returns the children for a given category.
	 *
	 * @return array
	 */
	protected function children()
	{
		$categories = $this->categories->children(
            $this->category_id
        );

        return $categories->pluck('id')
        	->prepend((int) $this->category_id)
        	->all();
	}

}
