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

use Antvel\Categories\Categories;
use Antvel\Categories\Models\Category;
use Antvel\Foundation\Http\Controller;
use Antvel\Categories\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
	/**
	 * The categories repository.
	 *
	 * @var Categories
	 */
	protected $categories = null;

	/**
     * Creates a new instance.
     *
     * @param Categories $categories
     *
     * @return void
     */
	public function __construct(Categories $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Shows categories list.
     *
     * @return void
     */
	public function index()
	{
		return view('foundation.sections.categories.index', [
			'categories' => $this->categories->paginateWith('parent'),
		]);
	}

	/**
	 * Edits a given category.
	 *
	 * @param  Category $category
	 *
	 * @return void
	 */
	public function edit(Category $category)
	{
		$category = $category->load('parent');

		return view('foundation.sections.categories.edit', [
			'hasParent' => ! is_null($category->parent),
			'parents' => $this->categories->parents(),
			'category' => $category,
		]);
	}

	/**
     * Updates the given category.
     *
     * @param  CategoriesRequest $request
     * @param  Category $category
     *
     * @return void
     */
	public function update(CategoriesRequest $request, Category $category)
	{
		$update = $this->categories->update(
			$request->all(), $category
		);

		return back();
	}
}
