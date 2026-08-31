<?php

namespace App\Modules\Sacraments\Domain\Exceptions;

use Exception;

class SacramentNotFoundException extends Exception
{
    public function __construct(string $message = 'Sacrament record not found')
    {
        parent::__construct($message);
    }
}
