<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Unit\Products;

use Antvel\Tests\TestCase;
use Antvel\Product\Models\Product;

class ProductsTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Antvel\Product\Products');
	}

	/** @test */
	function products_repository_implements_the_correct_model()
	{
	    $this->assertNotNull($this->repository->getModel());
		$this->assertInstanceOf(Product::class, $this->repository->getModel());
	}

	/** @test */
	function it_can_create_a_new_product()
	{
		//a user can create a new product.
		//it has to belong to a valid category.
		//features have to be validated throught the ones that are in DB.

		$product = $this->repository->create([
			'category_id' => 1,
			'created_by' => 1,
			'updated_by' => 2,
			'name' => 'name',
			'description' => 'des',
			'cost' => 5,
			'price' => 10,
			'stock' => 5,
			'low_stock' => 1,
			'bar_code' => 'barcode',
			'brand' => 'apple',
			'condition' => 'new',
			'tags' => 'a,b,c',
			'features' => '{}',
		]);

		$this->assertEquals('name', $product->name);
	}
}
