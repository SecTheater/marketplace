<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class SaleRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SaleRepository';
    }
}
