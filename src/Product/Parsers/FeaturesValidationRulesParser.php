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

use InvalidArgumentException;
use Illuminate\Support\Collection;

class FeaturesValidationRulesParser
{
	/**
	 * The feature validation rules.
	 *
	 * @var null
	 */
	protected $rules = null;

	/**
	 * The allowed feature validations rules.
	 *
	 * @var array
	 */
	protected $allowed = ['required', 'max', 'min'];

	/**
	 * Parses the given rules.
	 *
	 * @param  array $rules
	 *
	 * @return self
	 */
	public static function parse(array $rules = [])
	{
		$parser = new static;

		$parser->rules = $parser->mapRules($rules);

		return $parser;
	}

	/**
	 * Creates an instance for the given rules.
	 *
	 * @param  string $rules
	 *
	 * @return self
	 */
	public static function decode(string $rules)
	{
		$parser = new static;

		$parser->rules = Collection::make(explode('|', $rules));

		return $parser;
	}

	/**
	 * Returns a collection with the given rules.
	 *
	 * @param  array $rules
	 *
	 * @return Collection
	 */
	protected function mapRules($rules) : Collection
	{
		return Collection::make($rules)
			->only($this->allowed)
			->flatMap(function ($item, $key) {
				if ($key == 'required' && $item) {
					$rule[] = 'required';
				} else {
					$rule[] = $key . ':' . $item;
				}
				return $rule;
			});
	}

	/**
	 * Returns the feature validation rules in a json format.
	 *
	 * @return string
	 */
	public function toString() : string
	{
		return $this->rules->implode('|');
	}

	/**
	 * Returns the feature validation rules in a array format.
	 *
	 * @return array
	 */
	public function all() : Collection
	{
		return $this->rules;
	}

	public static function allowed()
	{
		$parser = new static;

		return Collection::make($parser->allowed);
	}
}
