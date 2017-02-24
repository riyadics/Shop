<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer;

use Illuminate\Http\Request;
use Antvel\Components\Customer\Requests\ProfileRequest;

class CustomersController
{
	protected $customerRepository = null;

    private $view_panel = [
        'left'   => ['width' => '2', 'class' => 'user-panel'],
        'center' => ['width' => '10'],
    ];

	public function __construct(CustomerRepository $customerRepository)
    {
    	$this->customerRepository = $customerRepository;
    }

	public function index()
	{
		return view('user.profile', [
        	'user' => $this->customerRepository->find(),
        	'panel' => $this->view_panel
        ]);
	}

    public function update($customer)
    {
        dd($customer);

        // //user update
        // \Session::flash('message', trans('user.saved'));
        // $user->fill($request->all());
        // $user->pic_url = $request->get('pic_url');
        // $user->password = bcrypt($request->get('password'));
        // $user->save();

        // //bussiness update
        // if ($request->get('business_name') !== null && trim($request->get('business_name')) != '') {
        //     $business = Business::find($user->id);
        //     $business->business_name = $request->get('business_name');
        //     $business->save();
        // }

        // //person update
        // if ($request->get('first_name') !== null && trim($request->get('first_name')) != '') {
        //     $person = Person::find($user->id);
        //     $person->first_name = $request->get('first_name');
        //     $person->last_name = $request->get('last_name');
        //     $person->birthday = $request->get('birthday');
        //     $person->sex = $request->get('sex');
        //     $person->save();
        // }

        // return redirect()->back();
    }
}