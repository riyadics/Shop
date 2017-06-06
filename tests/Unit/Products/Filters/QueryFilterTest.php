<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Unit\Products\Filters;

use Antvel\Tests\TestCase;
use Antvel\User\Models\User;
use Antvel\Product\Models\Product;
use Antvel\Categories\Models\Category;

class QueryFilterTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Antvel\Product\Products');
	}

	public function test_it_uses_the_correct_model()
	{
		$this->assertNotNull($this->repository->getModel());
		$this->assertInstanceOf(Product::class, $this->repository->getModel());
	}

	public function test_it_can_retrieve_products_by_conditions()
	{
		factory(Product::class)->create([
			'condition' => 'new'
		]);

		$products = $this->repository->filter([
			'condition' => 'new',
		]);

		$this->assertEquals($products->first()->condition, 'new');
		$this->assertCount(1, $products);
	}

	public function test_it_can_retrieve_products_by_brands()
	{
		factory(Product::class)->create([
			'brand' => 'apple'
		]);

		$products = $this->repository->filter([
			'brand' => 'apple',
		]);

		$this->assertEquals($products->first()->brand, 'apple');
		$this->assertCount(1, $products);
	}

	public function test_it_can_retrieve_products_by_searching()
	{
		factory(Product::class)->create([
			'name' => 'iPhone 7',
			'description' => 'The iPhone 7 description'
		]);

		$products = $this->repository->filter([
			'search' => 'iPhone',
		]);

		$this->assertTrue(strpos($products->first()->description, 'iPhone') !== false);
		$this->assertTrue(strpos($products->first()->name, 'iPhone') !== false);
		$this->assertCount(1, $products);
	}

	public function test_it_can_retrieve_products_by_price()
	{
		factory(Product::class)->create(['price' => 10]);
		factory(Product::class)->create(['price' => 20]);
		factory(Product::class)->create(['price' => 30]);

		$byMin = $this->repository->filter(['min' => 10]);
		$byMax = $this->repository->filter(['max' => 20]);
		$byMaxAndMax = $this->repository->filter(['min' => 22, 'max' => 30]);

		$this->assertCount(3, $byMin);
		$this->assertCount(2, $byMax);
		$this->assertCount(1, $byMaxAndMax);
	}

	public function test_it_can_retrieve_all_products()
	{
		$product = factory(Product::class, 2)->create();

		$all = $this->repository->filter();
		$first = $all->first();

		$this->assertInstanceOf('Antvel\Categories\Models\Category', $first->category);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $all);
		$this->assertCount(2, $all);
	}

	public function test_it_can_retrieve_products_by_categories()
	{
		$tools = factory(Category::class)->create([
			'name' => 'tools'
		]);

		$software = factory(Category::class)->create([
			'name' => 'software'
		]);

		$toolsProducts = factory(Product::class, 2)->create([
			'category_id' => $tools->id
		]);

		$softwareProducts = factory(Product::class, 2)->create([
			'category_id' => $software->id
		]);

		$byTools = $this->repository->filter([
			'category' => $tools->id . '|' . $tools->name,
		]);

		$this->assertCount(2, $byTools);
		$byTools->each(function ($item) use ($tools) {
			$this->assertEquals($tools->id, $item->category_id);
		});
	}

	public function test_it_can_retrieve_products_by_categories_and_its_children()
	{
		$tools = factory(Category::class)->create([
			'name' => 'tools'
		]);

		$software = factory(Category::class)->create([
			'category_id' => $tools->id,
			'name' => 'software',
		]);

		$toolsProducts = factory(Product::class, 2)->create([
			'category_id' => $tools->id
		]);

		$softwareProducts = factory(Product::class, 2)->create([
			'category_id' => $software->id
		]);

		$byToolsAndChildren = $this->repository->filter([
			'category' => $tools->id . '|' . $tools->name,
		]);

		$this->assertCount(4, $byToolsAndChildren);
		$byToolsAndChildren->each(function ($item) use ($tools, $software) {
			$this->assertTrue($item->category_id == $tools->id || $item->category_id == $software->id);
		});
	}

	public function test_it_can_retrieve_products_by_a_given_advanced_searching()
	{
		//Categories setup
		$category = factory(Category::class)->create(['name' => 'Entertainment']);
		$subCategories = factory(Category::class, 2)->create(['category_id' => $category->id]);

		//Products by category setup
		$list = factory(Product::class, 4)->create([
			'description' => 'Entertainment Product',
			'category_id' => $category->id,
			'condition' => 'new',
			'brand' => 'LG',
		]);

		$products = $this->repository->filter([
			'min' => $list->pluck('price')->min(),
			'max' => $list->pluck('price')->max(),
			'category' => $category->id,
			'search' => 'Entertainment',
			'condition' => 'new',
			'brands' => 'LG',
		]);

		$this->assertCount(4, $products);
	}

	// public function test_it_can_retrieve_the_logged_in_user_inactive_products()
	// {
	// 	$user = factory(User::class)->create();
	// 	$this->be($user);

	// 	$inactives = factory(Product::class)->create(['status' => 0]);

	// 	$userActives = factory(Product::class)->create([
	// 		'created_by' => auth()->user()->id,
 //        	// 'updated_by' => auth()->user()->id,
	// 	]);

	// 	$userInactive = factory(Product::class)->create([
	// 		'created_by' => $user->id,
 //        	// 'updated_by' => $user->id,
	// 		'status' => 0,
	// 	]);

	// 	$products = $this->repository->userProducts([
	// 		'inactives' => 1
	// 	]);

	// 	dd($products->first());

	// 	$this->assertCount(1, $products);
	// 	$this->assertEquals($user->id, $products->first()->user_id);
	// }

	// public function test_it_can_retrieve_the_logged_in_user_low_stock_products()
	// {
	// 	$user = factory(User::class)->create();
	// 	$this->be($user);

	// 	//other products
	// 	factory(Product::class)->create([
	// 		'stock' => 4,
	// 		'low_stock' => 5
	// 	]);

	// 	//user products with enough stock
	// 	factory(Product::class)->create([
	// 		'stock' => 10,
	// 		'low_stock' => 5,
	// 		'user_id' => auth()->user()->id
	// 	]);

	// 	//user products with low stock
	// 	factory(Product::class)->create([
	// 		'stock' => 5,
	// 		'low_stock' => 5,
	// 		'user_id' => auth()->user()->id
	// 	]);

	// 	$products = $this->repository->userProducts([
	// 		'low_stock' => 1
	// 	]);

	// 	$this->assertCount(1, $products);
	// 	$this->assertEquals($user->id, $products->first()->user_id);

	// }

}
