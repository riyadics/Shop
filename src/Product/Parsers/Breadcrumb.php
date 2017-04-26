<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Parsers;

class Breadcrumb
{
	/**
	 * The illuminate request component.
	 *
	 * @var \Illuminate/Database/Eloquent/Collection
	 */
	protected $request = null;

	/**
	 * Cretaes a new instance.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	 * Parses the given collection.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public static function parse($request) : array
	{
		$parser = new static ($request);

		return $parser->all();
	}

	/**
	 * Parses the given request.
	 *
	 * @return array
	 */
	protected function all() : array
	{
		//TODO: refactor this when working on the front end.

		$breadcrumb = $this->request->except('page');

		if ($this->request->has('category')) {
			$category = explode('|', $this->request->category);
			$breadcrumb['category_name'] = $category[1];
		}

		return $breadcrumb;
	}
}
