<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Models;

use Antvel\Product\QueryFilter;
use Antvel\Categories\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['num_of_reviews'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'user_id', 'name', 'description', 'price',
        'stock', 'features', 'barcode', 'condition', 'rate_val',
        'rate_count', 'low_stock', 'status', 'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['details', 'created_at'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Filter users upon type requested.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  Illuminate\Http\Request $request
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        return (new QueryFilter($request))->apply($query);
    }










///////////

    // public function scopeSearch($query, $seed)
    // {
    //     $fields = ['name', 'description', 'features', 'brand', 'tags'];

    //      if (trim($seed) !== '') {
    //         foreach ($fields as $value) {
    //             $query->orWhere($value, 'like', '%'.$seed.'%');
    //         }
    //     }
    // }

    public function scopeActives($query)
    {
        return $query->where('status', 1)->where('stock', '>', 0);
    }

    public function scopeRefine($query, $filters)
    {
        foreach ($filters as $key => $value) {

            switch ($key) {
                case 'category':

                    $category = \Cache::remember('filtered_by_category_id_' . $value, 15, function () use ($value){
                        return Category::select('id')
                            ->with('children.children')
                            ->where('id', $value)
                            ->first();
                    });

                    $children = $category->children->pluck('id')->all();
                    dd($children);
                    $query->whereIn('category_id', $children);

                break;
                case 'conditions':
                    $query->where('condition', 'LIKE', $value);
                break;
                case 'brands':
                   $query->where('brand', 'LIKE', $value);
                break;
                case 'min':
                case 'max':
                    $min = array_key_exists('min', $filters) ? (trim($filters['min']) != '' ? $filters['min'] : '') : '';
                    $max = array_key_exists('max', $filters) ? (trim($filters['max']) != '' ? $filters['max'] : '') : '';
                    if ($min != '' && $max != '') {
                        $query->whereBetween('price', [$min, $max]);
                    } elseif ($min == '' && $max != '') {
                        $query->where('price', '<=', $max);
                    } elseif ($min != '' && $max == '') {
                        $query->where('price', '>=', $min);
                    }
                break;
                default:
                    if ($key != 'category_name' && $key != 'search' && $key != 'page') {
                        //changing url encoded character by the real ones
                        $value = urldecode($value);
                        //applying filter to json field
                        $query->whereRaw("features LIKE '%\"".$key.'":%"%'.str_replace('/', '%', $value)."%\"%'");
                    }
                break;
            }
        }
    }

    public function getNumOfReviewsAttribute()
    {
        return $this->rate_count.' '.\Lang::choice('store.review', $this->rate_count);
    }
}
