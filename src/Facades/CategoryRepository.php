<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class CategoryRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CategoryRepository';
    }
}