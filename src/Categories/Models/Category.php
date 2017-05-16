<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Categories\Models;

use Antvel\User\Models\User;
use Antvel\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	/**
     * The database table.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'category_id', 'name', 'description', 'icon',
        'image', 'status', 'type',
    ];

    /**
     * A category belongs to an user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns a list of the children categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Returns a parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Returns the products list for a given category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Applies a light columns selection to the given query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeLightSelection($query)
    {
        return $query->select('categories.id', 'categories.name', 'categories.category_id');
    }

    /**
     * Returns actives categories.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeActives($query)
    {
        return $query->where('status', 1);
    }

     /**
     * Filter categories by the given request.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  array $request
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        $query->actives()->where(function ($query) use ($request) {
            foreach ($request as $key => $value) {
                $query->orWhere($key, 'like', '%' . $value . '%');
            }
            return $query;
        });

        return $query;
    }
}
