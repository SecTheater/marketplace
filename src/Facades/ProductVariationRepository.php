<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class ProductVariationRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductVariationRepository';
    }
}