<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class WishlistRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'WishlistRepository';
    }
}