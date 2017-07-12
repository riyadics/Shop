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
use Illuminate\Support\Facades\Storage;
use Antvel\Product\Models\{ Product, ProductPictures };

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
    function it_can_retrieve_a_given_product_default_picture()
    {
    	$product = factory(Product::class)->create();
    	factory(ProductPictures::class)->create(['product_id' => $product->id]);
    	$default = factory(ProductPictures::class)->states('default')->create(['product_id' => $product->id]);

    	$this->assertEquals($default->path, $product->default_picture);
    }

    /** @test */
    function it_retrieves_the_first_pictures_if_default_is_not_set()
    {
    	$product = factory(Product::class)->create();
    	$pictures = factory(ProductPictures::class, 2)->create(['product_id' => $product->id]);

    	$this->assertSame($pictures->first()->path, $product->default_picture);
    }

    /** @test */
    function it_returns_a_stub_image_if_pictures_were_not_found_when_retrieving_a_product_default_picture()
    {
    	$product = factory(Product::class)->create();

    	$this->assertSame('images/no-image.jpg', $product->default_picture);
    }

    /** @test */
    function it_can_update_the_given_product_default_picture()
    {
    	$product_01 = factory(Product::class)->create();
    	factory(ProductPictures::class)->create(['product_id' => $product_01->id]);
    	factory(ProductPictures::class)->states('default')->create(['product_id' => $product_01->id]);

    	$product_02 = factory(Product::class)->create();
    	factory(ProductPictures::class)->create(['product_id' => $product_02->id]);
    	factory(ProductPictures::class)->states('default')->create(['product_id' => $product_02->id]);

    	$this->assertFalse($product_01->pictures->first()->default);
    	$this->assertTrue($product_01->pictures->last()->default);
    	$this->assertFalse($product_02->pictures->first()->default);
    	$this->assertTrue($product_02->pictures->last()->default);

    	$product_01->updateDefaultPicture(
    		$product_01->pictures->first()->id
    	);

    	$this->assertTrue($product_01->fresh()->pictures->first()->default);
    	$this->assertFalse($product_01->fresh()->pictures->last()->default);
    	$this->assertFalse($product_02->pictures->first()->default);
    	$this->assertTrue($product_02->pictures->last()->default);
    }

    /** @test */
    function it_can_delete_pictures_from_a_given_product()
    {
		$product_01 = factory(Product::class)->create();
    	$picture_01 = factory(ProductPictures::class)->create(['product_id' => $product_01->id]);
    	factory(ProductPictures::class)->states('default')->create(['product_id' => $product_01->id]);

    	$product_02 = factory(Product::class)->create();
    	factory(ProductPictures::class)->create(['product_id' => $product_02->id]);
    	factory(ProductPictures::class)->states('default')->create(['product_id' => $product_02->id]);

    	$this->assertFalse($product_01->pictures->first()->default);
    	$this->assertTrue($product_01->pictures->last()->default);
    	$this->assertFalse($product_02->pictures->first()->default);
    	$this->assertTrue($product_02->pictures->last()->default);

    	$product_01->deletePictures([
    	   $toDelete = $product_01->pictures->first()->id
    	]);

    	$this->assertNull($product_01->fresh()->pictures->where('id', $toDelete)->first());
    	$this->assertCount(1, $product_01->fresh()->pictures);
    	$this->assertCount(2, $product_02->pictures);
    }

	/** @test */
	function a_repository_can_create_new_products()
	{
		$user = factory('Antvel\User\Models\User')->states('seller')->create();
		$this->actingAs($user);

		$product = $this->repository->create(array_merge($this->data(), [
			'pictures' => [
				'storing' => [
					$this->uploadFile($disk = 'images/products/1'),
					$this->uploadFile($disk),
				]
			],
		]));

		$this->assertEquals('Foo Bar Biz', $product->description);
		$this->assertEquals('Foo Bar', $product->name);
		$this->assertEquals('foo,bar', $product->tags);
		$this->assertEquals(84900, $product->cost);
		$this->assertEquals(94900, $product->price);

		//assert whether the product features were parsed right.
		$this->assertEquals('8x8x8', $product->features['dimensions']);
		$this->assertEquals('black', $product->features['color']);
		$this->assertEquals(12, $product->features['weight']);

		//assert whether the product pictures exist.
		$this->assertCount(2, $product->pictures);
		foreach ($product->pictures as $picture) {
			Storage::disk($disk)->assertExists(
				$this->image($picture->path)
			);
		}

		$this->cleanDirectory($disk);
	}

	/** @test */
	function a_repository_is_able_to_update_products_data()
	{
		$user = factory('Antvel\User\Models\User')->states('seller')->create();
		$product = $this->createProductWithPictures();
		$old_pictures = $product->pictures;
		$this->actingAs($user);

		$data = array_merge($this->data(), [
			'default_picture' => $product->pictures->first()->id,
			'pictures' => [
				'storing' => [
					$product->pictures->first()->id => $this->persistentUpload($disk = 'images/products/' . $product->id),
					//the second product picture should stay the same.
					$product->pictures->last()->id => $this->persistentUpload($disk),
				]
			],
		]);

		$this->repository->update($data, $product);

		$product = $product->fresh();
		$new_pictures = $product->pictures;

		//assertions on product body info.
		$this->assertEquals('Foo Bar Biz', $product->description);
		$this->assertEquals('Foo Bar', $product->name);
		$this->assertEquals('foo,bar', $product->tags);
		$this->assertEquals(94900, $product->price);
		$this->assertEquals(84900, $product->cost);

		//assertions on product features.
		$this->assertEquals('8x8x8', $product->features['dimensions']);
		$this->assertEquals('black', $product->features['color']);
		$this->assertEquals(12, $product->features['weight']);

		//assertions on product pictures.
		$this->assertCount(3, $new_pictures);
		$this->assertTrue($old_pictures[0]['path'] !== $new_pictures[0]['path']); //asserting the first picture was updated
		$this->assertSame($old_pictures[1]['path'], $new_pictures[1]['path']); //asserting the second picture stayed the same
		$this->assertTrue($old_pictures[2]['path'] !== $new_pictures[2]['path']); //asserting the last picture was updated

		foreach ($new_pictures as $picture) {
			Storage::disk($disk)->assertExists($this->image($picture['path']));
		}

		$this->cleanDirectory($disk);
	}

	/** @test */
	function a_repository_can_delete_images_from_a_given_product()
	{
		$user = factory('Antvel\User\Models\User')->states('seller')->create();
		$product = $this->createProductWithPictures();
		$old_pictures = $product->pictures;

		$this->actingAs($user);

		$data = array_merge($this->data(), [
			'pictures' => [
				'deleting' => [
					$product->pictures->first()->id => true,
					//the second product picture should stay the same.
					$product->pictures->last()->id => true,
				]
			],
		]);

		$this->repository->update($data, $product);
		$new_pictures = $product->fresh()->pictures;

		Storage::persistentFake($disk = 'images/products/' . $product->id);

		$this->assertCount(1, $new_pictures);
		$this->assertFalse(in_array($old_pictures->first()->path, $new_pictures->pluck('path')->toArray()));
		$this->assertFalse(in_array($old_pictures->last()->path, $new_pictures->pluck('path')->toArray()));
		$this->assertSame($old_pictures[1]['path'], $new_pictures->first()->path); //asserting the second picture stayed the same
		Storage::disk($disk)->assertExists($this->image($new_pictures->first()->path));
		Storage::disk($disk)->assertMissing($this->image($old_pictures->last()->path));
		Storage::disk($disk)->assertMissing($this->image($old_pictures->last()->path));

		$this->cleanDirectory($disk);
	}

	protected function createProductWithPictures($attr = [], $times = 3)
	{
		$product = factory(Product::class)->create($attr);

		for ($i=0; $i < $times; $i++) {
			factory(ProductPictures::class)->create([
				'product_id' => $product->id,
				'path' => $this->persistentUpload('images/products')->store('images/products/' . $product->id)
			]);
		}

		return $product;
	}

	protected function data()
	{
		return [
			'category' => factory('Antvel\Categories\Models\Category')->create()->id,
			'name' => 'Foo Bar',
			'description' => 'Foo Bar Biz',
			'cost' => 849,
			'price' => 949,
			'stock' => 10,
			'low_stock' => 2,
			'brand' => 'fake brand',
			'condition' => 'new',
			'features' => [
				'weight' => '12',
				'dimensions' => '8x8x8',
				'color' => 'black',
			]
		];
	}
}
