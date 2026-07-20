<?php

namespace Tests\Unit\Modules\Members\Domain\ValueObjects;

use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DateOfBirthTest extends TestCase
{
    public function test_can_create_valid_dob()
    {
        $dob = new DateOfBirth('1990-05-15');
        $this->assertEquals('1990-05-15', $dob->getValue());
    }

    public function test_calculates_correct_age()
    {
        $year = (new DateTimeImmutable)->format('Y') - 30;
        $dob = new DateOfBirth("$year-01-01");
        $this->assertEquals(30, $dob->getAge());
    }

    public function test_future_date_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $futureYear = (new DateTimeImmutable)->format('Y') + 1;
        new DateOfBirth("$futureYear-01-01");
    }

    public function test_invalid_format_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        new DateOfBirth('15-05-1990');
    }
}
