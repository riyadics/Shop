<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Categories;

use Antvel\Categories\Models\Category;

class Categories
{
	/**
	 * Creates a new category with a given attributes.
	 *
	 * @param  array $attr
	 * @return Category
	 */
	public function create(array $attr)
	{
		return Category::create($attr);
	}

	/**
     * Finds a category by a given constraints.
     *
     * @param mixed $constraints
     * @param array $loaders
     * @return null|Category
     */
	public function find($constraints, ...$loaders)
	{
        if (! is_array($constraints)) {
            $constraints = ['id' => $constraints];
        }

        //We fetch the user using a given constraint.
        $category = Category::where($constraints)->first();

        //We throw an exception if the user was not found to avoid whether
        //somebody tries to look for a non-existent user.
        abort_if( ! $category, 404);

        //If loaders were requested, we will lazy load them.
        if (count($loaders) > 0) {
            $category->load(implode(',', $loaders));
        }

        return $category;
	}
}
