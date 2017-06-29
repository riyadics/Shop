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
use Antvel\Product\Products;
use Antvel\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

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
		$this->assertInstanceOf('Antvel\Product\Models\Product', $this->repository->getModel());
	}

	/** @test */
    function can_get_product_cost_in_dollars()
    {
        $product = factory(Product::class)->make([
            'cost' => 6750,
        ]);

        $this->assertEquals('67.50', $product->cost_in_dollars);
    }

	/** @test */
    function can_get_product_price_in_dollars()
    {
        $product = factory(Product::class)->make([
            'price' => 6750,
        ]);

        $this->assertEquals('67.50', $product->price_in_dollars);
    }





	/** @test */
	function a_repository_can_create_new_products()
	{
		$user = factory('Antvel\User\Models\User')->states('seller')->create();
		$this->actingAs($user);

		$product = $this->repository->create([
			'category' => 1,
			'name' => 'iPhone Seven',
			'description' => 'The iPhone 7',
			'cost' => 649,
			'price' => 749,
			'stock' => 5,
			'low_stock' => 1,
			'brand' => 'apple',
			'condition' => 'new',
			'features' => [
				'weight' => '10',
				'dimensions' => '5x5x5',
				'color' => 'black',
			],
			'pictures' => [
				$this->uploadFile('images/products'),
				$this->uploadFile('images/products'),
				$this->uploadFile('images/products'),
			],
		]);

		tap($product->fresh(), function ($product) {

			$this->assertEquals('iPhone Seven', $product->name);
			$this->assertEquals('The iPhone 7', $product->description);
			$this->assertEquals('iphone,seven', $product->tags);
			$this->assertEquals(64900, $product->cost);
			$this->assertEquals(74900, $product->price);

			//assert whether the product features were parsed right.
			$this->assertTrue(count($product->features['images']) == 3);
			$this->assertTrue(is_array($product->features['images']));
			$this->assertEquals(10, $product->features['weight']);
			$this->assertEquals('5x5x5', $product->features['dimensions']);
			$this->assertEquals('black', $product->features['color']);

			//assert whether the product pictures exist.
			for ($i=0; $i < count($product->features['images']); $i++) {
				Storage::disk('images/products')->assertExists(
					$this->image($product->features['images'][$i])
				);
			}
		});
	}
}
