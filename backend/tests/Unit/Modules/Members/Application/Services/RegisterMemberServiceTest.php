<?php

namespace Tests\Unit\Modules\Members\Application\Services;

use App\Modules\Members\Application\DTOs\RegisterMemberDTO;
use App\Modules\Members\Application\Services\RegisterMemberService;
use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Policies\MemberRegistrationPolicy;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RegisterMemberServiceTest extends TestCase
{
    private MemberRepositoryInterface $repository;

    private MemberRegistrationPolicy $policy;

    private RegisterMemberService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MemberRepositoryInterface::class);
        $this->policy = new MemberRegistrationPolicy;
        $this->service = new RegisterMemberService($this->repository, $this->policy);
    }

    public function test_can_register_member()
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Member $member) {
                return new Member(
                    id: 1,
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
            });

        $dto = new RegisterMemberDTO(
            firstName: 'John',
            lastName: 'Doe',
            middleName: null,
            dob: '1990-01-01',
            gender: 'male',
            maritalStatus: 'single',
            phone: '0712345678',
            position: 'Choir',
            employmentStatus: 'employed',
            employmentNotes: null,
            householdId: null
        );

        $member = $this->service->execute($dto);

        $this->assertEquals(1, $member->getId());
        $this->assertEquals('John', $member->getName()->getFirstName());
        $this->assertEquals('1990-01-01', $member->getDob()->getValue());
        $this->assertEquals(Gender::MALE, $member->getGender());
        $this->assertEquals(EmploymentStatus::EMPLOYED, $member->getEmploymentStatus());
    }
}
