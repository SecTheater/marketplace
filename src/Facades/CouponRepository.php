<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class CouponRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CouponRepository';
    }
}