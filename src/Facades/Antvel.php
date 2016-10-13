<?php

namespace Antvel\Facades;

use Illuminate\Support\Facades\Facade;

class Antvel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'antvel';
    }
}