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

use Antvel\Contracts\Repository;
use Antvel\Product\Models\ProductFeatures;
use Antvel\Product\Parsers\FeaturesValidationRulesParser;

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
       return ProductFeatures::create($this->data($attributes));
    }

    /**
     * Update a Model in the database.
     *
     * @param array $attributes
     * @param mixed $idOrModel
     * @param array $options
     *
     * @return bool
     */
    public function update(array $attributes = [], $idOrModel, array $options = [])
    {
    	$feature = $this->modelOrFind($idOrModel);

        return $feature->update($this->data($attributes), $options);
    }

    /**
     * Returns the data to be updated in the database.
     *
     * @param  array $attributes
     *
     * @return array
     */
    protected function data(array $attributes = []) : array
    {
        $attributes['validation_rules'] = $attributes['validation_rules'] ?? null;

        return array_merge($attributes, [
            'validation_rules' => FeaturesValidationRulesParser::parse($attributes['validation_rules'])->toString()
        ]);
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

    /**
     * Exposes the features allowed to be in the products filtering.
     *
     * @param  integer $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterable($limit = 5, $pluck = null)
    {
        return ProductFeatures::where('status', true)
            ->where('filterable', true)
            ->take($limit)
            ->get();
    }

    /**
     * Returns an array with the validation rules for the filterable features.
     *
     * @return array
     */
    public function filterableValidationRules() : array
    {
        return $this->filterable()
            ->filter(function ($item) {
                return trim($item->validation_rules) != '' && ! is_null($item->validation_rules);
            })->mapWithKeys(function ($item) {
                return [$item->name => $item->validation_rules];
            })->all();
    }
}
