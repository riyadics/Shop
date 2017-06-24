<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Requests;

use Antvel\Http\Request;
use Antvel\Product\Features;
use Antvel\Product\Products;
use Antvel\Product\Attributes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

class ProductsRequest extends Request
{
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
        return array_merge(
            $this->rulesForBody(), $this->rulesForFeatures(), $this->forPictures()
        );
    }

    /**
     * Builds the validation rules for the product information.
     *
     * @return array
     */
    protected function rulesForBody()
    {
        return [
            'name' => 'required|alpha_num',
            'description' => 'required',
            'cost' => 'required|integer',
            'price' => 'required|integer',
            'brand' => 'required|alpha_num',
            'stock' => 'required|integer',
            'low_stock' => 'required|integer',

            'category' => [
                'required',
                Rule::exists('categories', 'id')->where('id', $this->request->get('category')),
            ],

            'condition' => [
                'required',
                Rule::in(Attributes::make('condition')->keys()),
            ],

        ];
    }

    /**
     * Builds the validation rules for the product features.
     *
     * @return array
     */
    protected function rulesForFeatures() : array
    {
        return $this->container->make(Features::class)->filterableValidationRules();
    }

    /**
     * Builds the validation rules for the product pictures.
     *
     * @return array
     */
    protected function forPictures() : array
    {
        $pictures = Collection::make($this->all())->filter(function($item, $key) {
            return $key == 'pictures';
        })->get('pictures');

        if (is_null($pictures)) {
            return [];
        }

        //We check the request taking into account the maximum number of files allowed per product.
        $rules = [];

        for ($i=1; $i <= Products::MAX_PICS; $i++) {
            //we check whether the request has a picture for the given index. If the index is
            //present in the request files, we create the corresponding rule for it.
            if (isset($pictures[$i])) {
                $rules['pictures.' . $i] = [
                    'mimes:jpeg,png,jpg',
                    Rule::dimensions()->maxWidth(1000)->maxHeight(500)
                ];
            }
        }

        return $rules;
    }

    /**
     * Format the pictures validation errors messages.
     *
     * @return array
     */
    public function messages() : array
    {
        return array_merge($this->picturesErrorsMessages(), [
            'condition.in' => trans('products.validation_errors.condition')
        ]);
    }

    /**
     * Returns the messages errors for given pictures inputs.
     *
     * @return array
     */
    protected function picturesErrorsMessages()
    {
        $messages = [];

        for ($i=1; $i <= Products::MAX_PICS; $i++) {
            $messages['pictures.' . $i . '.*'] = trans('products.validation_errors.pictures_upload', ['i' => $i]);
        }

        return $messages;
    }
}
