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

use Illuminate\Support\Collection;
use Antvel\Support\Images\ImageControl;

class FeaturesParser
{
	/**
	 * The maximum of pictures files per product.
	 */
	const MAX_PICS = 5;

	/**
     * The files directory.
     *
     * @var string
     */
    protected $picturesFolder = 'images/products';

    /**
     * The pictures to be uploaded.
     *
     * @var array
     */
    protected $pictures = [];

    /**
     * The products feature to be stored.
     *
     * @var array
     */
	protected $features = [];

	/**
	 * Creates a new instance.
	 *
	 * @param  array $attributes
	 *
	 * @return self
	 */
	public static function parse($attributes)
	{
		$parser = new static;

		$parser->pictures = $attributes->get('pictures') ?: [];
		$parser->features = $attributes->get('features') ?: [];

		return $parser;
	}

	/**
	 * Returns the features of the product in JSON format.
	 *
	 * @return string
	 */
	public function toJson() : string
	{
		return  json_encode(array_merge(
			$this->parsePictures(),
			$this->features
		));
	}

	/**
	 * Upload the products pictures.
	 *
	 * @return array
	 */
	public function parsePictures() : array
    {
    	if (count($this->pictures) == 0) {
			return [];
		}

    	for ($i=0; $i < self::MAX_PICS; $i++) {
    		if (isset($this->pictures[$i])) {
    			$pictures['images'][] = ImageControl::prepare(['_pictures_file' => $this->pictures[$i]])->store($this->picturesFolder);
    		}
    	}

    	return $pictures;
    }
}
