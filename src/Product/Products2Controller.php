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

// use Antvel\Product\Models\Product;
// use Antvel\Categories\Models\Category;

use Antvel\Product\Products;

class Products2Controller extends Controller
{
	protected $products = null;

	protected $panel = [
        'left'   => ['width' => '2', 'class'=>'categories-panel'],
        'center' => ['width' => '10'],
    ];



	public function __construct(Products $products)
	{
		$this->products = $products;
	}

	/**
	 * Loads the foundation dashboard.
	 *
	 * @return void
	 */
	public function index(Request $request, QueryFilter $filters)
	{
		$products = $this->products->filter($filters);

		// dd($products);

		//parse constraints
		// $constraints['search'] = $request->get('search');

		// $children = [];

		// // gets category children
		// if ($request->has('category')) {

		// 	$category = explode('|', urldecode($request->category));

		// 	$constraints['category_name'] = last($category);

		// 	$category_id = head($category);

		// 	//get category children
		// 	if (! is_null($category_id)) {
		// 		$category = \Cache::remember('filtered_by_category_id_' . $category_id, 15, function () use ($category_id){
	 //                return Category::select('id')
	 //                    ->with('children.children')
	 //                    ->where('id', $category_id)
	 //                    ->first();
	 //            });

	 //            $children = $category->children
	 //            	->pluck('id')
	 //            	->push((int) $category_id)
	 //            	->reverse()
	 //            	->all();
  //       	}
		// }

		// $constraints = array_merge(
		// 	$constraints, $request->except('category')
		// );

		// // dd($constraints, $children);

  //       \DB::enableQueryLog();

  //       $products = Product::actives()
  //       	->when(count($children) > 0, function ($query) use ($children) {
  //               return $query->whereIn('category_id', $children);
  //           })

  //           ->when(isset($constraints['conditions']), function ($query)  use ($constraints) {
  //           	return $query->where('condition', 'LIKE', $constraints['conditions']);
  //           })

  //           ->when(isset($constraints['brands']), function ($query)  use ($constraints) {
  //           	return $query->where('brand', 'LIKE', $constraints['brands']);
  //           })

  //           // add filter by features
  //           // ->when(isset($constraints['min']), function ($query)  use ($constraints) {
  //           // 	return $query->where('condition', 'LIKE', $constraints['min']);
  //           // })

  //           ->when(! is_null($constraints['search']), function ($query) use ($constraints) {
  //               return $query->where(function($query) use ($constraints) {
  //               	return $query->search($constraints['search']);
  //               });
  //           })

  //           ->orderBy('rate_val', 'desc');

  //       //get the products list
		// $products = $products->paginate(28);

		// // dd(\DB::getQueryLog(), $products);



		// $filters['conditions'] = array_count_values($products->pluck('condition')->all());

		// $filters['brands'] = array_count_values($products->pluck('brand')->all());

		// $features = [];
  //       $irrelevant_features = ['images', 'dimensions', 'weight', 'brand']; //this has to be in company setting module
  //       foreach ($products->pluck('features') as $feature) {
  //           $feature = collect(json_decode($feature))->except($irrelevant_features);
  //           foreach ($feature as $key => $value) {
  //               $features[$key][] = $value;
  //           }
  //       }

  //       //filters for features
  //       foreach ($features as $key => $value) {
  //       	// dd('1>', $features, $key, $value);
  //           foreach ($features[$key] as $row) {

  //               // dd('2>', $features, $features[$key], '>>', $key , $row );
  //               if (is_string($row)) {
  //                   $filters[$key][$row] = isset($filters[$key][$row]) ? $filters[$key][$row] + 1 : 1;
  //               }
  //           }
  //       }

        // dd($filters);

		//need to add suggestions
		//set user preferences

		return view('products.index', [
			'filters' => $filters,
			'products' => $products,
			'panel' => $this->panel,
			'refine' => $constraints,
			'suggestions' => []
		]);
	}
}
