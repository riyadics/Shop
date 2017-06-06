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
use Antvel\User\UsersRepository;
use Antvel\Product\Models\Product;

class SuggestionsTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Antvel\Product\Products');

		$this->category = factory('Antvel\Categories\Models\Category', 'child')->create();
	}

	public function test_it_can_suggest_products_based_on_a_given_key()
	{
		$products = factory(Product::class, 2)->create([
			'category_id' => $this->category->id,
			'tags' => 'foo,bar'
		]);

		$products2 = factory(Product::class, 2)->create([
			'tags' => 'home,phone,bar'
		]);

		$results = $this->repository->filter([
			'category' => $this->category->id .'|'.$this->category->name
		]);

		$suggestion = $this->repository->suggestFor($results);

		$this->assertTrue(count($suggestion['my_searches']) == 4);
		$this->assertTrue($suggestion['my_searches']->pluck('tags')->contains('foo,bar'));
	}

	public function test_it_can_suggest_products_based_on_an_user_preferences()
	{
		$products = factory(Product::class, 3)->create([
			'category_id' => $this->category->id
		]);

		$user = factory('Antvel\User\Models\User')->create();

		$this->actingAs($user);

		UsersRepository::updatePreferences('product_viewed', $tags = $products->first()->tags);

		$suggestion = $this->repository->suggestForPreferences([
			'product_viewed'
		], 4);

		$this->assertInstanceOf('Illuminate\Support\Collection', $suggestion['product_viewed']);
		$this->assertTrue(in_array(
			$tags, $suggestion['product_viewed']->pluck('tags')->all()
		));
	}

	public function test_it_can_suggest_products_based_on_a_given_preferences()
	{
		$preferences = '{
			"product_viewed": "foo",
			"product_purchased": "dolore,explicabo",
			"product_categories": "2"
		}';

		factory(Product::class)->create([
			'category_id' => $this->category->id,
			'tags' => 'foo'
		]);

		factory(Product::class)->create([
			'category_id' => $this->category->id,
			'tags' => 'bar'
		]);

		factory(Product::class)->create([
			'category_id' => $this->category->id,
			'tags' => 'biz'
		]);

		$suggestion = $this->repository->suggestForPreferences([
			'product_viewed'
		]);

		$this->assertInstanceOf('Illuminate\Support\Collection', $suggestion['product_viewed']);
		$this->assertTrue(in_array(
			'foo', $suggestion['product_viewed']->pluck('tags')->all()
		));
	}

}
