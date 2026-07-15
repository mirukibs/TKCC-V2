<?php

namespace App\Modules\Members\Domain\Exceptions;

use Exception;

class InvalidMemberDataException extends Exception
{
    public static function reason(string $reason): self
    {
        return new self("Invalid member data: {$reason}");
    }
}
