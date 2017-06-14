<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Support\Images;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowController extends Controller
{
	/**
	 * Renders the given imagen.
	 *
	 * @param  Request $request
	 * @param  string $file
	 *
	 * @return void
	 */
	public function index(Request $request, $file)
	{
		$options = $request->all();

		return Image::make($file, $options)->render();
	}
}
