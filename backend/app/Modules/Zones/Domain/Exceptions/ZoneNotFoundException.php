<?php

namespace App\Modules\Zones\Domain\Exceptions;

use Exception;

class ZoneNotFoundException extends Exception
{
    public function __construct(string $message = "Zone not found.")
    {
        parent::__construct($message, 404);
    }
}
