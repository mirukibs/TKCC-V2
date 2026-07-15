<?php

namespace Tests\Unit\Members\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;
use InvalidArgumentException;

class PhoneNumberTest extends TestCase
{
    public function test_normalizes_tanzanian_local_number()
    {
        $phone = new PhoneNumber("0712 345 678");
        $this->assertEquals("+255712345678", $phone->getValue());
    }

    public function test_normalizes_tanzanian_number_missing_plus()
    {
        $phone = new PhoneNumber("255 712-345-678");
        $this->assertEquals("+255712345678", $phone->getValue());
    }

    public function test_accepts_valid_international_number()
    {
        $phone = new PhoneNumber("+12345678901");
        $this->assertEquals("+12345678901", $phone->getValue());
    }

    public function test_invalid_phone_number_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        new PhoneNumber("invalid-phone");
    }

    public function test_too_short_phone_number_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        new PhoneNumber("+123");
    }
}
