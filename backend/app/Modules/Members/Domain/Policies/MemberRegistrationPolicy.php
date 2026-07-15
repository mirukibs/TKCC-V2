<?php

namespace App\Modules\Members\Domain\Policies;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Exceptions\InvalidMemberDataException;

class MemberRegistrationPolicy
{
    /**
     * Enforce business rules for registering a new member.
     *
     * @throws InvalidMemberDataException
     */
    public function enforce(Member $member): void
    {
        // Example Rule: A member cannot be employed if they are under 15 years old.
        if ($member->getEmploymentStatus() === EmploymentStatus::EMPLOYED && $member->getDob() !== null) {
            if ($member->getDob()->getAge() < 15) {
                throw InvalidMemberDataException::reason('A member under 15 years old cannot be marked as EMPLOYED.');
            }
        }

        // Example Rule: If a member is a minor, we might require them to belong to a household.
        if ($member->getDob() !== null && $member->getDob()->getAge() < 18) {
            if ($member->getHouseholdId() === null) {
                throw InvalidMemberDataException::reason('A minor (under 18) must belong to a household.');
            }
        }
    }
}
