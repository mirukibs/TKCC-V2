<?php

namespace App\Modules\Members\Domain\ValueObjects;

use InvalidArgumentException;

class FullName
{
    private string $firstName;

    private string $lastName;

    private ?string $middleName;

    public function __construct(string $firstName, string $lastName, ?string $middleName = null)
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $middleName = $middleName ? trim($middleName) : null;

        if (empty($firstName)) {
            throw new InvalidArgumentException('First name cannot be empty.');
        }

        if (empty($lastName)) {
            throw new InvalidArgumentException('Last name cannot be empty.');
        }

        if (strlen($firstName) > 100 || strlen($lastName) > 100 || ($middleName && strlen($middleName) > 100)) {
            throw new InvalidArgumentException('Name parts cannot exceed 100 characters.');
        }

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = empty($middleName) ? null : $middleName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getValue(): string
    {
        if ($this->middleName) {
            return "{$this->firstName} {$this->middleName} {$this->lastName}";
        }

        return "{$this->firstName} {$this->lastName}";
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function equals(FullName $other): bool
    {
        return $this->firstName === $other->getFirstName() &&
               $this->lastName === $other->getLastName() &&
               $this->middleName === $other->getMiddleName();
    }
}
