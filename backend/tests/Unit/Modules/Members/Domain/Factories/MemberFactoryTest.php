<?php

namespace Tests\Unit\Modules\Members\Domain\Factories;

use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\Factories\MemberFactory;
use PHPUnit\Framework\TestCase;

class MemberFactoryTest extends TestCase
{
    public function test_can_create_member_with_all_fields()
    {
        $member = MemberFactory::create(
            firstName: 'John',
            lastName: 'Doe',
            middleName: 'Smith',
            dob: '1990-01-01',
            gender: 'male',
            maritalStatus: 'single',
            phone: '0712345678',
            position: 'Choir',
            employmentStatus: 'employed',
            employmentNotes: 'Software Engineer',
            householdId: 5
        );

        $this->assertEquals('John', $member->getName()->getFirstName());
        $this->assertEquals('Doe', $member->getName()->getLastName());
        $this->assertEquals('Smith', $member->getName()->getMiddleName());
        $this->assertEquals('1990-01-01', $member->getDob()->getValue());
        $this->assertEquals(Gender::MALE, $member->getGender());
        $this->assertEquals(MaritalStatus::SINGLE, $member->getMaritalStatus());
        $this->assertEquals('+255712345678', $member->getPhone()->getValue());
        $this->assertEquals('Choir', $member->getPosition());
        $this->assertEquals(EmploymentStatus::EMPLOYED, $member->getEmploymentStatus());
        $this->assertEquals('Software Engineer', $member->getEmploymentNotes());
        $this->assertEquals(5, $member->getHouseholdId());
    }

    public function test_can_create_member_with_minimal_fields()
    {
        $member = MemberFactory::create(
            firstName: 'Jane',
            lastName: 'Doe'
        );

        $this->assertEquals('Jane', $member->getName()->getFirstName());
        $this->assertEquals('Doe', $member->getName()->getLastName());
        $this->assertNull($member->getName()->getMiddleName());
        $this->assertNull($member->getDob());
        $this->assertNull($member->getGender());
        $this->assertNull($member->getMaritalStatus());
        $this->assertNull($member->getPhone());
        $this->assertNull($member->getPosition());
        $this->assertNull($member->getEmploymentStatus());
        $this->assertNull($member->getEmploymentNotes());
        $this->assertNull($member->getHouseholdId());
    }
}
