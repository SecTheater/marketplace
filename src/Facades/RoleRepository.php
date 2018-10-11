<?php

namespace SecTheater\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class RoleRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RoleRepository';
    }
}
