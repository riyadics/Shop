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

use Mockery as m;
use Antvel\Tests\TestCase;
use Antvel\AddressBook\AddressBook;


class AddressBookTest extends TestCase
{
   public function testDown()
    {
        $aux = 'default';
        $this->assertEquals('default', $aux);
    }

}