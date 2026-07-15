<?php

namespace App\Modules\Members\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

class DateOfBirth
{
    private DateTimeImmutable $date;

    public function __construct(string $dateString)
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $dateString);
        
        if (!$date || $date->format('Y-m-d') !== $dateString) {
            throw new InvalidArgumentException("Invalid date of birth format. Use YYYY-MM-DD.");
        }

        if ($date > new DateTimeImmutable()) {
            throw new InvalidArgumentException("Date of birth cannot be in the future.");
        }

        $this->date = $date;
    }

    public function getValue(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function getAge(): int
    {
        $now = new DateTimeImmutable();
        return $now->diff($this->date)->y;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function equals(DateOfBirth $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
