<?php

namespace App\Modules\Households\Domain\Exceptions;

use Exception;

class HouseholdNotFoundException extends Exception
{
    public static function withId(int $id): self
    {
        return new self("Household with ID {$id} not found.");
    }
}
