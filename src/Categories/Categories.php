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

use Antvel\Support\Pictures;
use Antvel\Contracts\Repository;
use Antvel\Categories\Models\Category;

class Categories implements Repository
{
    /**
     * The files directory.
     *
     * @var string
     */
    protected $filesDirectory = 'img/categories';

    /**
     * Paginate the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|null $builder
     * @param  array $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($builder = null, $options = [])
    {
        $builder = $builder ?? new Category;

        $options = array_merge(['pageName' => 'page', 'columns' => ['*'],
            'perPage' => null, 'page' => null
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
    public function create(array $attributes = []) : Category
	{
        if (isset($attributes['_pictures_file'])) {
            $attributes['image'] = Pictures::make($attributes)->store($this->filesDirectory);
        }

        return Category::create($attributes);
	}

    /**
     * Update a Model in the database.
     *
     * @param array $attributes
     * @param Category|mixed $idOrModel
     * @param array $options
     * @return bool
     */
    public function update(array $attributes, $idOrModel, array $options = [])
    {
        $category = $this->model($idOrModel);

        if (isset($attributes['image'])) {
            $attributes['image'] = Pictures::make($attributes)->store($this->filesDirectory);
        }

        return $category->update($attributes, $options);
    }

    /**
     * Returns the model.
     *
     * @param  Category|mixed $idOrModel
     * @return Category
     */
    protected function model($idOrModel) : Category
    {
        return $idOrModel instanceof Category ?
            $idOrModel :
            $this->find($idOrModel)->first();
    }

	/**
     * Finds a category by a given constraints.
     *
     * @param mixed $constraints
     * @param mixed $columns
     * @param array $loaders
     *
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

    /**
     * Returns the master categories.
     *
     * @param  integer $limit
     * @return \Illuminate/Database/Eloquent/Collection
     */
    public function parents($limit = 50)
    {
        return Category::whereNull('category_id')->take($limit)->get();
    }
}
