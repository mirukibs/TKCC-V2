<?php

namespace App\Modules\Households\Domain\Exceptions;

use Exception;

class InvalidHouseholdDataException extends Exception
{
    public static function reason(string $reason): self
    {
        return new self("Invalid household data: {$reason}");
    }
}
