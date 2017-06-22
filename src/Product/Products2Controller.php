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

use Antvel\Http\Controller;
use Illuminate\Http\Request;
use Antvel\Product\Features;
use Antvel\Product\Attributes;
use Antvel\Categories\Categories;
use Antvel\User\UsersRepository as Users; //
use Antvel\Product\Requests\ProductsRequest;
use Antvel\Product\Parsers\Filters as FiltersParser;
use Antvel\Product\Parsers\Breadcrumb as BreadcrumbParser;

class Products2Controller extends Controller
{
	/**
	 * The products repository.
	 *
	 * @var Products
	 */
	protected $products = null;

	protected $panel = [
        'left'   => ['width' => '2', 'class'=>'categories-panel'],
        'center' => ['width' => '10'],
    ];

    /**
     * Creates a new instance.
     *
     * @param Products $products
     *
     * @return void
     */
	public function __construct(Products $products)
	{
		$this->products = $products;
	}

	/**
	 * Loads the foundation dashboard.
	 *
	 * @return void
	 */
	public function index(Request $request)
	{
		//I need to come back in here and check how I can sync the paginated products
		//with the filters. The issue here is paginated does not contain the whole
		//result, therefore, the filters count are worng.

		$products = $this->products->filter(
			$request->all()
		);

		// this line is required in order for the store to show
		// the counter on the side bar filters.

		$allProducts = $products->get();

		Users::updatePreferences('my_searches', $allProducts);

		return view('products.index', [
			'suggestions' => $this->products->suggestFor($allProducts),
			'refine' => BreadcrumbParser::parse($request->all()),
			'filters' => FiltersParser::parse($allProducts),
			'products' => $products->paginate(28),
			'panel' => $this->panel,
		]);
	}

	/**
	 * List the seller products.
	 *
	 * @return void
	 */
	public function indexDashboard(Request $request)
	{
		$products = $this->products->filter(
			$request->all()
		)
		->with('creator', 'updater')
		->paginate(20);

		return view('dashboard.sections.products.index', [
			'products' => $products,
		]);
	}

	/**
	 * Show the creation form.
	 *
	 * @param  Categories $categories
	 *
	 * @return void
	 */
	public function create(Categories $categories, Features $features)
	{
		return view('dashboard.sections.products.create', [
			'conditions' => Attributes::make('condition')->get(),
			'categories' => $categories->actives(),
			'features' => $features->filterable(),
			'MAX_PICS' => Products::MAX_PICS,
		]);
	}

	/**
	 * Stores a new product.
	 *
	 * @param  ProductsRequest $request
	 *
	 * @return void
	 */
	public function store(ProductsRequest $request)
	{
		//
	}
}
