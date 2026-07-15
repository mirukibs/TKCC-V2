<?php

namespace App\Modules\Members\Domain\ValueObjects;

use InvalidArgumentException;

class PhoneNumber
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = self::normalizeAndValidate($value);
    }

    private static function normalizeAndValidate(string $phone): string
    {
        // Remove spaces, dashes, and parentheses
        $phone = preg_replace('/[\s\-\(\)]+/', '', $phone);

        // Tanzanian local format (e.g., 0712345678 -> +255712345678)
        if (preg_match('/^0([67]\d{8})$/', $phone, $matches)) {
            return '+255'.$matches[1];
        }

        // Tanzanian format missing + (e.g., 255712345678 -> +255712345678)
        if (preg_match('/^255([67]\d{8})$/', $phone, $matches)) {
            return '+255'.$matches[1];
        }

        // International format (must start with + and have 10-15 digits)
        if (preg_match('/^\+[1-9]\d{9,14}$/', $phone)) {
            return $phone;
        }

        throw new InvalidArgumentException("Invalid phone number format: {$phone}. Provide a valid local Tanzanian or international number.");
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(PhoneNumber $other): bool
    {
        return $this->value === $other->getValue();
    }
}
