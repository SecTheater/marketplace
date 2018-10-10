<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class CartRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CartRepository';
    }
}