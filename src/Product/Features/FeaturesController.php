<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Product\Features;

use Antvel\Http\Controller;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
	/**
	 * The features repository.
	 *
	 * @var Features
	 */
	protected $features = null;

	/**
	 * Creates a new instance.
	 *
	 * @param Features $features
	 *
	 * @return void
	 */
	public function __construct(Features $features)
	{
		$this->features = $features;
	}

	/**
     * Shows categories list.
     *
     * @return void
     */
	public function index()
	{
        return view('foundation.sections.features.index', [
        	'features' => $this->features->all()
        ]);
	}
}
