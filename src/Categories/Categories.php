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

use Antvel\Contracts\Repository;
use Antvel\Categories\Models\Category;

class Categories implements Repository
{
    /**
     * Paginate the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|null $builder
     * @param  array $options
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($builder = null, $options = [])
    {
        $builder = $builder ?? new Category;

        $options = array_merge([
            'pageName' => 'page',
            'columns' => ['*'],
            'perPage' => null,
            'page' => null
        ], $options);

        return $builder->paginate($options['perPage'], $options['columns'], $options['pageName'], $options['page']);
    }

    /**
     * Paginates the given query and load relationship.
     *
     * @param  string|array $loaders
     * @param  array $constraints
     * @param  array $paginate
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateWith($loaders, $constraints = [], $paginate = [])
    {
        $categories = Category::with($loaders);

        if (count($constraints) > 0) {
            $categories->where($constraints);
        }

        return $this->paginate($categories, $paginate);
    }

	/**
	 * Creates a new category with a given attributes.
	 *
	 * @param  array $attributes
	 * @return Category
	 */
    public function create(array $attributes = [])
	{
		return Category::create($attributes);
	}

	/**
     * Finds a category by a given constraints.
     *
     * @param mixed $constraints
     * @param mixed $columns
     * @param array $loaders
     * @return null|Category
     */
    public function find($constraints, $columns = '*', ...$loaders)
	{
        if (! is_array($constraints)) {
            $constraints = ['id' => $constraints];
        }

        //We fetch the user using a given constraint.
        $category = Category::select($columns)->where($constraints)->get();

        //We throw an exception if the user was not found to avoid whether
        //somebody tries to look for a non-existent user.
        abort_if( ! $category, 404);

        //If loaders were requested, we will lazy load them.
        if (count($loaders) > 0) {
            $category->load(implode(',', $loaders));
        }

        return $category;
	}

    public function parents($limit = 50)
    {
        return Category::whereNull('category_id')->take($limit)->get();
    }
}
