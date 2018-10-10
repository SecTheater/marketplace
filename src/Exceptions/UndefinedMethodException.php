<?php

namespace SecTheater\Marketplace\Exceptions;

use Exception;

class UndefinedMethodException extends Exception
{
    protected $message = 'Call To Undefined Method';
}
