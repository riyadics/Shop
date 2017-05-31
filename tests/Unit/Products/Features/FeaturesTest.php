<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Unit\Products\Features;

use Antvel\Tests\TestCase;
use Antvel\Product\Models\ProductFeatures;

class FeaturesTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Antvel\Product\Features');
	}

	/** @test */
	function it_has_the_correct_model()
	{
	    $this->assertNotNull($this->repository->getModel());
		$this->assertInstanceOf(ProductFeatures::class, $this->repository->getModel());
	}

	/** @test */
	function it_can_list_the_saved_features()
	{
		factory(ProductFeatures::class)->create([
			'name' => 'color'
		]);

		$features = $this->repository->all();

		$this->assertCount(1, $features);
		$this->assertEquals('color', $features->first()->name);
	}

	/** @test */
	function it_can_create_a_new_feature()
	{
		$feature = $this->repository->create([
			'name' => 'feature',
			'input_type' => 'text',
			'product_type' => 'item',
			'validation_rules' => '{feature:required|max:100}',
			'help_message' => '{}',
			'status' => '{}'
		]);

		$this->assertTrue($feature->exists());
		$this->assertEquals('feature', $feature->name);
	}
}
