<?php

namespace Tests\Unit\Members\Domain\Entities;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use App\Modules\Members\Domain\ValueObjects\FullName;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;
use PHPUnit\Framework\TestCase;

class MemberTest extends TestCase
{
    public function test_can_create_member_entity_with_value_objects()
    {
        $member = new Member(
            id: 1,
            name: new FullName('John', 'Doe', 'Smith'),
            dob: new DateOfBirth('1990-01-01'),
            gender: Gender::MALE,
            maritalStatus: MaritalStatus::SINGLE,
            phone: new PhoneNumber('+255712345678'),
            position: 'Leader',
            employmentStatus: EmploymentStatus::EMPLOYED,
            employmentNotes: 'Works in IT',
            householdId: 5
        );

        $this->assertEquals(1, $member->getId());
        $this->assertEquals('John Smith Doe', $member->getName()->getValue());
        $this->assertEquals('1990-01-01', $member->getDob()->getValue());
        $this->assertEquals(Gender::MALE, $member->getGender());
        $this->assertEquals(MaritalStatus::SINGLE, $member->getMaritalStatus());
        $this->assertEquals('+255712345678', $member->getPhone()->getValue());
        $this->assertEquals('Leader', $member->getPosition());
        $this->assertEquals(EmploymentStatus::EMPLOYED, $member->getEmploymentStatus());
        $this->assertEquals('Works in IT', $member->getEmploymentNotes());
        $this->assertEquals(5, $member->getHouseholdId());
    }
}
