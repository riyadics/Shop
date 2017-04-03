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
use Antvel\Categories\Categories;
use Illuminate\Support\Collection;
use Antvel\Categories\Models\Category;

class CategoriesTest extends TestCase
{
	protected $repository = null;

	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make(Categories::class);
	}

	public function test_a_repository_can_create_categories()
	{
		$this->repository->create([
			'name' => 'Electronics',
			'description' => 'Electronics devices',
			'image' => '/img/pt-default/'.mt_rand(1, 20).'.jpg',
        	'icon' => array_rand(['glyphicon glyphicon-facetime-video', 'glyphicon glyphicon-bullhorn', 'glyphicon glyphicon-briefcase'], 1),
		]);

		$created = Category::latest()->first();

		$this->assertInstanceOf(Category::class, $created);
		$this->assertEquals($created->name, 'Electronics');
	}

	public function test_a_repository_can_create_a_sub_categories()
	{
		$this->repository->create([
			'name' => 'Electronics',
			'description' => 'Electronics devices',
			'image' => '/img/pt-default/'.mt_rand(1, 20).'.jpg',
        	'icon' => array_rand(['glyphicon glyphicon-facetime-video', 'glyphicon glyphicon-bullhorn', 'glyphicon glyphicon-briefcase'], 1),
		]);

		$parent = Category::latest()->first();

		$children = factory(Category::class)->create([
			'name' => 'Games',
			'category_id' => $parent->id
		]);

		$this->assertTrue(count($parent->children) == 1);
		$this->assertEquals($children->parent->name, 'Electronics');

		$parent->children->each(function($item, $key) use ($parent) {
			$this->assertEquals($item->name, 'Games');
			$this->assertEquals($item->category_id, $parent->id);
		});
	}
}
