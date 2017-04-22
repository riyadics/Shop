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
		// \DB::enableQueryLog();

		$products = $this->products->filter($request);

		// dd('controller', \DB::getQueryLog(), $products);

		//parse breadcrumb
		$breadcrumb['search'] = $request->get('search');
		$breadcrumb['category_name'] = 'category_name';

        $filters = FiltersParser::parse($products);

		//need to add suggestions
		//set user preferences

		return view('products.index', [
			'filters' => $filters,
			'products' => $products,
			'panel' => $this->panel,
			'refine' => $breadcrumb,
			'suggestions' => []
		]);
	}
}
