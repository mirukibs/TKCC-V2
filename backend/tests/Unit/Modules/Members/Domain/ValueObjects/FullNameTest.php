<?php

namespace Tests\Unit\Modules\Members\Domain\ValueObjects;

use App\Modules\Members\Domain\ValueObjects\FullName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    public function test_can_create_valid_full_name_without_middle_name()
    {
        $name = new FullName('John', 'Doe');
        $this->assertEquals('John Doe', $name->getValue());
        $this->assertEquals('John', $name->getFirstName());
        $this->assertEquals('Doe', $name->getLastName());
        $this->assertNull($name->getMiddleName());
    }

    public function test_can_create_valid_full_name_with_middle_name()
    {
        $name = new FullName('John', 'Doe', 'Smith');
        $this->assertEquals('John Smith Doe', $name->getValue());
        $this->assertEquals('Smith', $name->getMiddleName());
    }

    public function test_empty_first_name_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        new FullName('   ', 'Doe');
    }

    public function test_empty_last_name_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        new FullName('John', '');
    }

    public function test_name_exceeding_max_length_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $longName = str_repeat('a', 101);
        new FullName($longName, 'Doe');
    }

    public function test_equals_returns_true_for_same_names()
    {
        $name1 = new FullName('John', 'Doe', 'Smith');
        $name2 = new FullName('John', 'Doe', 'Smith');

        $this->assertTrue($name1->equals($name2));
    }
}
