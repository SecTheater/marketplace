<?php

namespace SecTheater\Marketplace\Exceptions;

use Exception;

class ProductAlreadyExistsException extends Exception
{
    protected $message = 'Product Already Exists, so are attributes';
}
