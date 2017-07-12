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

class FeaturesParser
{
	/**
	 * Creates a new instance.
	 *
	 * @param  mixed $features
	 *
	 * @return mixed
	 */
	public static function parse($features)
	{
		return (new static)->normalize($features);
	}

	/**
	 * Normalize the given features.
	 *
	 * @param  mixed $features
	 *
	 * @return mixed
	 */
	protected function normalize($features)
	{
		if (is_null($features) || count($features) == 0) {
			return null;
		}

		return Collection::make($features)->filter(function ($item) {
			return trim($item) != '';
		})->toJson();
	}
}
