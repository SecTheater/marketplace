<?php

namespace SecTheater\Marketplace\Exceptions;

use Exception;

class PropertyIsNotEnabledExpcetion extends Exception
{
    protected $message = 'Property Is not enabled , you might need to check your config, or spelling.';
}
