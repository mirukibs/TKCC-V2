<?php

namespace App\Modules\Members\Domain\Exceptions;

use Exception;

class MemberNotFoundException extends Exception
{
    public static function withId(int $id): self
    {
        return new self("Member with ID {$id} was not found.");
    }
}
