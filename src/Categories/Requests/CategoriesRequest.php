<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Categories\Requests;

use Illuminate\Validation\Rule;
use Antvel\Categories\Categories;
use Antvel\Foundation\Http\Request;

class CategoriesRequest extends Request
{
    /**
     * The categories repository.
     *
     * @var Categories
     */
    protected $categories = null;

    /**
     * Creates a new instance.
     *
     * @param Categories $categories
     *
     * @return void
     */
    public function __construct(Categories $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'background' => 'dimensions:min_width=100,min_height=200',
            'background' => 'mimes:jpeg,png,jpg',
            'description' => 'required',
            'name' => [
                'required',
                Rule::exists('categories')->where(function ($query) {
                    $query->where('name', 'like', $this->request->get('name'));
                }),
            ],
        ];
    }
}
