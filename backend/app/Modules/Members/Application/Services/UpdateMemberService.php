<?php

namespace App\Modules\Members\Application\Services;

use App\Modules\Members\Application\DTOs\UpdateMemberDTO;
use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Exceptions\MemberNotFoundException;
use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;

class UpdateMemberService
{
    public function __construct(
        private MemberRepositoryInterface $repository
    ) {}

    public function execute(UpdateMemberDTO $dto): Member
    {
        $existingMember = $this->repository->findById($dto->id);
        if (! $existingMember) {
            throw new MemberNotFoundException("Member with ID {$dto->id} not found.");
        }

        $member = MemberFactory::create(
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            middleName: $dto->middleName,
            dob: $dto->dob,
            gender: $dto->gender,
            maritalStatus: $dto->maritalStatus,
            phone: $dto->phone,
            position: $dto->position,
            employmentStatus: $dto->employmentStatus,
            employmentNotes: $dto->employmentNotes,
            householdId: $dto->householdId
        );

        // Retain the original ID
        $updatedMember = new Member(
            id: $existingMember->getId(),
            name: $member->getName(),
            dob: $member->getDob(),
            gender: $member->getGender(),
            maritalStatus: $member->getMaritalStatus(),
            phone: $member->getPhone(),
            position: $member->getPosition(),
            employmentStatus: $member->getEmploymentStatus(),
            employmentNotes: $member->getEmploymentNotes(),
            householdId: $member->getHouseholdId()
        );

        $this->repository->save($updatedMember);

        return $updatedMember;
    }
}
