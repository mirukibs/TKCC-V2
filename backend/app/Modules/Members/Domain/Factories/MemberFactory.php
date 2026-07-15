<?php

namespace App\Modules\Members\Domain\Factories;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use App\Modules\Members\Domain\ValueObjects\FullName;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;

class MemberFactory
{
    public static function create(
        string $firstName,
        string $lastName,
        ?string $middleName = null,
        ?string $dob = null,
        ?string $gender = null,
        ?string $maritalStatus = null,
        ?string $phone = null,
        ?string $position = null,
        ?string $employmentStatus = null,
        ?string $employmentNotes = null,
        ?int $householdId = null
    ): Member {
        return new Member(
            id: null,
            name: new FullName($firstName, $lastName, $middleName),
            dob: $dob ? new DateOfBirth($dob) : null,
            gender: $gender ? Gender::from($gender) : null,
            maritalStatus: $maritalStatus ? MaritalStatus::from($maritalStatus) : null,
            phone: $phone ? new PhoneNumber($phone) : null,
            position: $position,
            employmentStatus: $employmentStatus ? EmploymentStatus::from($employmentStatus) : null,
            employmentNotes: $employmentNotes,
            householdId: $householdId
        );
    }
}
