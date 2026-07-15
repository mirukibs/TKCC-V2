<?php

namespace App\Modules\Members\Application\DTOs;

class RegisterMemberDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $middleName = null,
        public readonly ?string $dob = null,
        public readonly ?string $gender = null,
        public readonly ?string $maritalStatus = null,
        public readonly ?string $phone = null,
        public readonly ?string $position = null,
        public readonly ?string $employmentStatus = null,
        public readonly ?string $employmentNotes = null,
        public readonly ?int $householdId = null
    ) {}
}
