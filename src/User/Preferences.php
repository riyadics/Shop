<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\User;

use Illuminate\Support\Collection;
use Illuminate\Container\Container;

class Preferences
{
	/**
	 * The laravel auth component.
	 *
	 * @var Authenticable
	 */
	protected $auth = null;

	/**
	 * The allowed schema for users preferences.
	 *
	 * @var array
	 */
	protected $allowed = [
		'my_searches' => '',
		'product_shared' => '',
		'product_viewed' => '',
		'product_purchased' => '',
		'product_categories' => '',
	];

	/**
	 * The user preferences.
	 *
	 * @var string
	 */
	protected $preferences = null;

	/**
	 * Creates a new instance from a given user preferences.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->auth = Container::getInstance()->make('auth');
	}

	/**
	 * Creates a new instance from a given user preferences.
	 *
	 * @param  mixed $preferences
	 *
	 * @return self
	 */
	public static function parse($preferences = null)
	{
		$static = new static;

		$static->allowed = Collection::make($static->allowed);

		$static->preferences = $static->prune($preferences);

		return $static;
	}

	/**
	 * Prunes the given preferences.
	 *
	 * @param  mixed $preferences
	 *
	 * @return Collection
	 */
	protected function prune($preferences = null) : Collection
	{
		$preferences = $this->normalizePref($preferences);

		return Collection::make($preferences)->filter(function($item, $key) {
			return $this->allowed->has($key);
		});
	}

	/**
	 * Normalize the given preferences.
	 *
	 * @param  mixed $preferences
	 *
	 * @return array
	 */
	protected function normalizePref($preferences = null) : array
	{
		if ($this->auth->check()) {
			return $this->loggedUserPreferences();
		}

		if (! is_null($preferences) && is_string($preferences)) {
			return json_decode($preferences, true);
		}

		return $this->allowed->all();
	}

	/**
	 * Returns the logged user preferences.
	 *
	 * @return array
	 */
	protected function loggedUserPreferences() : array
	{
		$preferences = $this->auth->user()->preferences ?? $this->allowed->all();

		return json_decode($preferences, true);
	}

	/**
	 * Updates the user preferences for a given key and data.
	 *
	 * @param  string $key
	 * @param  mixed $data
	 *
	 * @return self
	 */
	public function update(string $key, $data)
	{
		if ($this->allowed->has($key)) {
			$this->updatePreferencesForKey(
				$key, $this->normalizedTags($data)
			);

			$this->updateCategories(
				$data->pluck('category_id')->unique()
			);
		}

		return $this;
	}

	/**
	 * Returns a collection of tags.
	 *
	 * @param  mixed $data
	 *
	 * @return Collection
	 */
	protected function normalizedTags($data) : Collection
	{
		return $data->has('tags')
			? Collection::make($data->get('tags'))
			: $data->pluck('tags');
	}

	/**
	 * Updates the user references for a given key.
	 *
	 * @param  string $key
	 * @param  Collection $tags
	 *
	 * @return void
	 */
	protected function updatePreferencesForKey(string $key, Collection $tags)
	{
		$tags = $this->parseTags($tags)
			->merge($this->preferences[$key])
			->unique()
			->implode(',');

		$this->preferences[$key] = trim($tags, ',');
	}

	/**
	 * Parse the given tags collection.
	 *
	 * @param Collection $tags
	 *
	 * @return Collection
	 */
	protected function parseTags(Collection $tags) : Collection
	{
		$tags = str_replace('"', '', $tags->implode(','));

		return Collection::make(explode(',', $tags))->unique();
	}

	/**
	 * Updates the user categories key with the given collection.
	 *
	 * @param  Collection $data
	 *
	 * @return void
	 */
	protected function updateCategories(Collection $data)
	{
		$categories = Collection::make($this->preferences['product_categories'])
			->merge($data)
			->unique()
			->implode(',');

		$this->preferences['product_categories'] = trim($categories, ',');
	}

	/**
	 * Cast the user preferences to an array.
	 *
	 * @return array
	 */
	public function toArray() : array
	{
		return $this->preferences->all();
	}

	/**
	 * Cast the user preferences to json.
	 *
	 * @return string
	 */
	public function toJson() : string
	{
		return json_encode($this->preferences->all());
	}

	/**
	 * Plucks the given key from the user preferences.
	 *
	 * @param  string $key
	 *
	 * @return Collection
	 */
	public function pluck($key) : Collection
	{
		if (! $this->preferences->has($key)) {
			return new Collection;
		}

		return Collection::make(
			explode(',', $this->preferences[$key])
		);
	}

	/**
	 * Takes the given keys from the preferences array.
	 *
	 * @param  array $keys
	 *
	 * @return Collection
	 */
	public function all(array $keys = []) : Collection
	{
		if (count($keys) == 0) {
			$keys = $this->preferences->keys();
		}

		return Collection::make($keys)->flatMap(function ($item) {
			if (isset($this->preferences[$item])) {
				$result[$item] = explode(',', $this->preferences[$item]);
			}
			return $result ?? [];
		});
	}
}
