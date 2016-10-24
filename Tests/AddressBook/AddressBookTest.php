<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests\AddressBook;

use DB;
use Mockery as m;
use Antvel\Tests\TestCase;
use Antvel\AddressBook\AddressBook;

class AddressBookTest extends TestCase
{
	protected function addressBook()
	{
		return $this->app->make(AddressBook::class);
	}

	public function test_create_a_new_address()
    {
    	// DB::table('addresses')->insert([
    	// 	'nickname'    => 'gocanto',
     //        'email'       => 'gustavoocanto@gmail.com',
     //        'role'        => 'person',
     //        'password'    => \Hash::make('123456'),
     //        'pic_url'     => '/img/pt-default/1.jpg',
     //        'twitter'     => '@gocanto',
     //        'facebook'    => '',
     //        'preferences' => '{"product_viewed":[],"product_purchased":[],"product_shared":[],"product_categories":[],"my_searches":[]}',
    	// ]);

    	// $this->addressBook()->create([

    	// ]);
        // $users = \DB::table('addresses')->where('user_id', '=', 4)->first();
        // $this->assertEquals(['98935'], $users->zipcode);
    }

}