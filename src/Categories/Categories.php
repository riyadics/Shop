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

use Antvel\Support\Repository;
use Illuminate\Support\Collection;
use Antvel\Categories\Models\Category;
use Antvel\Support\Images\Manager as Images;

class Categories extends Repository
{
    /**
     * The files directory.
     *
     * @var string
     */
    protected $filesDirectory = 'images/categories';

    /**
     * Creates a new instance.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->setModel($category);
    }

	/**
	 * Creates a new category with a given attributes.
	 *
	 * @param  array $attributes
     *
	 * @return Category
	 */
    public function create(array $attributes = []) : Category
	{
        $category = Category::create($this->sanitize($attributes));

        if (isset($attributes['pictures'])) {
            $this->update($attributes, $category);
        }

        return $category;
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
        $category = $this->modelOrFind($idOrModel);

        if (isset($attributes['pictures'])) {
            $attributes['image'] = $this->updatePicturesFor($category, $attributes['pictures']);
        }

        return $category->update(
            $this->sanitize($attributes), $options
        );
    }

    /**
     * Sanitize the given attributes
     * @param  array $attributes
     *
     * @return array
     */
    protected function sanitize($attributes) : array
    {
        return Collection::make($attributes)->except('pictures')->all();
    }

    /**
     * Update the image for the given category.
     *
     * @param  Category $category
     * @param  array $attr
     *
     * @return string
     */
    protected function updatePicturesFor($category, $attr)
    {
        $current = $category->image;

        $image = Images::parse($attr)->on($this->filesDirectory . '/' . $category->id);

        if ($image->wantsDeletion()) {
            $image->delete($current);
            return null;
        }

        $picture = $image->update($current);

        return $picture['path'];
    }

    /**
     * Returns the master categories.
     *
     * @param  mixed $columns
     * @param  int $limit
     *
     * @return \Illuminate/Database/Eloquent/Collection
     */
    public function parents($columns = '*', $limit = 50)
    {
        return Category::select($columns)
            ->whereNull('category_id')
            ->take($limit)
            ->get();
    }

    /**
     * Returns the master categories except the given one.
     *
     * @param  int $category_id
     * @param  mixed $columns
     * @param  int $limit
     *
     * @return \Illuminate/Database/Eloquent/Collection
     */
    public function parentsExcept($category_id, $columns = '*', $limit = 50)
    {
        return Category::select($columns)
            ->whereNull('category_id')
            ->where('id', '<>', $category_id)
            ->take($limit)
            ->get();
    }

    /**
     * Returns the children for a given category.
     *
     * @param  Category|mixed $idOrModel $idOrModel
     * @param array $columns
     * @param int $limit
     *
     * @return \Illuminate/Database/Eloquent/Collection
     */
    public function children($idOrModel, $columns = 'id', int $limit = 50)
    {
        $category_id = $idOrModel instanceof Category ? $idOrModel->id : $idOrModel;

        return Category::select($columns)->with('children')
            ->where('category_id', $category_id)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Filters categories by a given request.
     *
     * @param array $request
     * @param mixed $columns
     * @param integer $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function havingProducts(array $request = [], $columns = '*', $limit = 10)
    {
        return Category::whereHas('products', function($query) {
            return $query->actives();
        })
        ->select($columns)
        ->filter($request)
        ->orderBy('name')
        ->take($limit)
        ->get();
    }

    /**
     * Returns a collection with the active categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function actives()
    {
        return $this->find([ 'status' => true ]);
    }
}
