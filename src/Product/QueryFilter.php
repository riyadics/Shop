<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
	/**
     * The request information.
     *
     * @var Illuminate\Http\Request
     */
    protected $request = null;

    /**
     * Eloquent builder pointer.
     *
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected $query = null;

    /**
	 * The allowed filters.
	 *
	 * @var array
	 */
	protected $allowed = [
		'category' => \Antvel\Product\Filters\Category::class,

		// 'search' => \Antvel\Product\Filters\Search::class,
		// 'conditions' => \Antvel\Product\Filters\Search::class,
		// 'brands' => \Antvel\Product\Filters\Search::class,
		// 'min' => \Antvel\Product\Filters\Search::class,
		// 'max' => \Antvel\Product\Filters\Search::class
	];

    /**
     * Create a new instance.
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
    	$this->request = $this->parseRequest($request);
    }

    /**
     * Parses the incoming request.
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function parseRequest(Request $request) : array
    {
    	$allowed = Collection::make($this->allowed)->keys()->all();

    	return $request->intersect($allowed);
    }

    /**
     * Apply an eloquent query to model.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder|null
     */
    public function apply(Builder $query)
    {
    	$this->query = $query;

    	foreach ($this->request as $key => $value) {
    		if ($this->canQueryFor($key)) {
    			// echo '- ' . $key . ' -';
    			$this->query = $this->filter($key)->query($this->query);
    		}
    	}

        return $this->query;
    }

    /**
     * Checks whether a given filter can query.
     *
     * @param  string $filter
     *
     * @return bool
     */
    protected function canQueryFor($filter) : bool
    {
    	return !! method_exists($this->allowed[$filter], 'query');
    }

    /**
     * Returns the filter instance for a given key.
     *
     * @param  string $key
     *
     * @return mixed
     */
    protected function filter($key)
    {
    	return new $this->allowed[$key](
    		$this->request[$key]
    	);
    }
}
