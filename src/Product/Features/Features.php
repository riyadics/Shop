<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Features;

use Antvel\Support\Repository;
use Antvel\Product\Features\Models\ProductFeatures;

class Features extends Repository
{
	/**
	 * Creates a new instance.
	 *
	 * @param ProductFeatures $features
	 */
	public function __construct(ProductFeatures $features)
	{
		$this->setModel($features);
	}

	/**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
    	//
    }

    /**
     * Update a Model in the database.
     *
     * @param array $attributes
     * @param Category|mixed $idOrModel
     * @param array $options
     *
     * @return bool
     */
    public function update(array $attributes, $idOrModel, array $options = [])
    {
    	//
    }

    /**
     * Returns all the products features.
     *
     * @param  integer $limit
     * @param  mixed $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($limit = null, $columns = '*')
    {
    	return ProductFeatures::select($columns)
    		->take($limit)
    		->get();
    }


}
