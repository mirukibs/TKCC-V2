<?php

namespace App\Modules\Members\Domain\Entities;

use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use App\Modules\Members\Domain\ValueObjects\FullName;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;

class Member
{
    private ?int $id;

    private FullName $name;

    private ?DateOfBirth $dob;

    private ?Gender $gender;

    private ?MaritalStatus $maritalStatus;

    private ?PhoneNumber $phone;

    private ?string $position;

    private ?EmploymentStatus $employmentStatus;

    private ?string $employmentNotes;

    private ?int $householdId;

    private array $aggregates = [];

    public function __construct(
        ?int $id,
        FullName $name,
        ?DateOfBirth $dob = null,
        ?Gender $gender = null,
        ?MaritalStatus $maritalStatus = null,
        ?PhoneNumber $phone = null,
        ?string $position = null,
        ?EmploymentStatus $employmentStatus = null,
        ?string $employmentNotes = null,
        ?int $householdId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->maritalStatus = $maritalStatus;
        $this->phone = $phone;
        $this->position = $position;
        $this->employmentStatus = $employmentStatus;
        $this->employmentNotes = $employmentNotes;
        $this->householdId = $householdId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): FullName
    {
        return $this->name;
    }

    public function getDob(): ?DateOfBirth
    {
        return $this->dob;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function getMaritalStatus(): ?MaritalStatus
    {
        return $this->maritalStatus;
    }

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function getEmploymentStatus(): ?EmploymentStatus
    {
        return $this->employmentStatus;
    }

    public function getEmploymentNotes(): ?string
    {
        return $this->employmentNotes;
    }

    public function getHouseholdId(): ?int
    {
        return $this->householdId;
    }

    public function getAggregates(): array
    {
        return $this->aggregates;
    }

    public function setAggregates(array $aggregates): void
    {
        $this->aggregates = $aggregates;
    }
}
