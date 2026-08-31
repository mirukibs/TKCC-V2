<?php

namespace App\Modules\Sacraments\Domain\Exceptions;

use Exception;

class InvalidSacramentDataException extends Exception
{
    public function __construct(string $message = 'Invalid sacrament data provided')
    {
        parent::__construct($message);
    }
}
