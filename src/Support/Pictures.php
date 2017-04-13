<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Pictures
{
	/**
	 * The upload process information.
	 *
	 * @var Collection
	 */
	protected $data = null;

	/**
	 * The allowed fields.
	 *
	 * @var array
	 */
	protected $allowed = ['_pictures_file', '_pictures_current', '_pictures_delete'];

	/**
	 * Creates a new instance with the given attributes.
	 *
	 * @param  array $attributes
	 * @return slef
	 */
	public static function make(array $attributes)
	{
		$pictures = new static;

		$pictures->data = Collection::make($attributes)->only($pictures->allowed);

		return $pictures;
	}

	/**
	 * Stores the file in the given directory.
	 *
	 * @param  string $directory
	 * @return null|string
	 */
	public function store($directory)
	{
		if ($this->data->has('_pictures_delete')) {
			return $this->purge($directory);
		}

		return $this->upload($directory);
	}

	/**
	 * Uploads a file in the given directory.
	 *
	 * @param  string $directory
	 * @return string
	 */
	protected function upload($directory) : string
	{
		return $this->data
			->get('_pictures_file')
			->store($directory);
	}

	/**
	 * Cleans the given directory.
	 *
	 * @param  string $directory
	 * @return null
	 */
	protected function purge($directory)
	{
		$files = Collection::make(Storage::files($directory));

		$aux = $files->filter(function($value) {
			return Str::contains($value, $this->startWith());
		})->tap(function($collection) {
			Storage::delete($collection->all());
		});

		return null;
	}

	/**
	 * Returns the file name save in the database.
	 *
	 * @return string
	 */
	protected function startWith() : string
	{
		$current = explode('/', $this->data->get('_pictures_current'));

		$fileName = Arr::last($current);

		return Arr::first(explode('.', $fileName));
	}

	/**
	 * Returns the process information.
	 *
	 * @return Collection
	 */
	public function data()
	{
		return $this->data;
	}
}
