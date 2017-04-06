<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\WorkShop;

use Illuminate\Http\Request;
use Antvel\Foundation\Http\Controller;

class DashBoardController extends Controller
{
	/**
	 * Loads the workshop dashboard.
	 *
	 * @return void
	 */
	public function index()
	{
		echo 'Workshop DashBoard!';
	}
}
