<?php

namespace SecTheater\Marketplace\Exceptions;

use Exception;

class InsufficientProductQuantity extends Exception
{
    protected $message = 'Insufficient Quantity For the applied product.';
}
