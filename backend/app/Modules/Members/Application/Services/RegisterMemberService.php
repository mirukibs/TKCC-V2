<?php

namespace App\Modules\Members\Application\Services;

use App\Modules\Members\Application\DTOs\RegisterMemberDTO;
use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Domain\Policies\MemberRegistrationPolicy;

class RegisterMemberService
{
    public function __construct(
        private readonly MemberRepositoryInterface $repository,
        private readonly MemberRegistrationPolicy $policy
    ) {}

    public function execute(RegisterMemberDTO $dto): Member
    {
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

        $this->policy->enforce($member);

        return $this->repository->save($member);
    }
}
