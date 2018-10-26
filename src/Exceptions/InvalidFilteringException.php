<?php

namespace Cheppers\LaravelApiGenerator\Exceptions;

use \Exception;

class InvalidFilteringException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message, null, null);
    }
}
