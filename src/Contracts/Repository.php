<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Contracts;

interface Repository
{
    /**
     * Paginate the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|null $builder
     * @param  array $options
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($builder = null, $options = []);

	/**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = []);

    /**
     * Update a Model in the database.
     *
     * @param array $attributes
     * @param Category|mixed $idOrModel
     * @param array $options
     * @return bool
     */
    public function update(array $attributes, $idOrModel, array $options = []);

    /**
     * Find a Model in the Database using the given constraints.
     *
     * @param mixed $constraints
     * @param mixed $columns
     * @param array $loaders
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($constraints, $columns = '*', ...$loaders);
}
