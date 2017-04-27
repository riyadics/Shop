<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\Unit;

use Antvel\Tests\TestCase;
use Antvel\Product\Models\Product;

class ProductsTest extends TestCase
{
	/**
	 * The categories repository.
	 *
	 * @var \Antvel\Categories\Categories
	 */
	protected $repository = null;

	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Antvel\Product\Products');
	}

	public function test_it_has_the_correct_model()
	{
		$this->assertNotNull($this->repository->getModel());
		$this->assertInstanceOf(Product::class, $this->repository->getModel());
	}


}
