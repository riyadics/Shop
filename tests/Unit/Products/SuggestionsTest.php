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
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'foo,bar']);
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'tar,ring']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,glass']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,mouse']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'foo,biz']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'key,ring']);

		$results = $this->repository->filter([
			'category' => $this->category->id .'|'.$this->category->name
		])->get();

		$suggestion = $this->repository->suggestFor($results, 'my_searches');

		$this->assertCount(4, $suggestion);
		$this->assertCount(0, $suggestion->where('id', $suggestion->pluck('id')->all()));

		$suggestionsTags = explode(',', $suggestion->pluck('tags')->implode(','));

		$this->assertTrue(in_array('foo', $suggestionsTags));
		$this->assertTrue(in_array('ring', $suggestionsTags));
	}

	public function test_it_can_suggest_products_based_on_an_user_preferences_key()
	{
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'foo,bar']);
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'tar,ring']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,glass']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,mouse']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'foo,biz']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'key,ring']);

		$suggestion = $this->repository->suggestForPreferences('product_viewed', 2, '{"product_viewed": "foo,ring"}');

		$suggestionsTags = explode(',', $suggestion->pluck('tags')->implode(','));

		$this->assertInstanceOf('Illuminate\Support\Collection', $suggestion);
		$this->assertTrue(in_array('foo', $suggestionsTags));
		$this->assertTrue(in_array('ring', $suggestionsTags));
		$this->assertCount(2, $suggestion);
	}

	/** @test */
	function it_returns_an_array_with_all_the_suggested_product_based_on_given_keys()
	{
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'foo,bar']);
		factory(Product::class)->create(['category_id' => $this->category->id, 'tags' => 'tar,ring']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,glass']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'phone,mouse']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'foo,biz']);
		factory(Product::class)->create(['category_id' => 99, 'tags' => 'key,ring']);

		$preferences = '{"my_searches": "foo", "product_viewed": "ring", "product_categories": "99"}';

		$suggestion = $this->repository->suggestForPreferences(['my_searches', 'product_viewed', 'product_categories'], 2, $preferences);

		$my_searches = explode(',', $suggestion['my_searches']->pluck('tags')->implode(','));
		$product_viewed = explode(',', $suggestion['product_viewed']->pluck('tags')->implode(','));

		$this->assertTrue(is_array($suggestion));
		$this->assertTrue(isset($suggestion['my_searches']));
		$this->assertTrue(isset($suggestion['product_viewed']));
		$this->assertTrue(in_array('foo', $my_searches));
		$this->assertTrue(in_array('ring', $product_viewed));
		$this->assertTrue(in_array(99, $suggestion['product_categories']->pluck('category_id')->all()));
	}

}
