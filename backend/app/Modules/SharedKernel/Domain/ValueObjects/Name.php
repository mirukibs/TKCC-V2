<?php

namespace App\Modules\SharedKernel\Domain\ValueObjects;

use App\Modules\SharedKernel\Domain\Exceptions\InvalidNameException;

class Name
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw new InvalidNameException("Name cannot be empty.");
        }

        if (strlen($value) > 150) {
            throw new InvalidNameException("Name cannot exceed 150 characters.");
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Name $other): bool
    {
        return $this->value === $other->getValue();
    }
}
