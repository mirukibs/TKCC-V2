<?php

namespace Tests\Unit\Modules\Members\Application\Services;

use App\Modules\Members\Application\DTOs\UpdateMemberDTO;
use App\Modules\Members\Application\Services\UpdateMemberService;
use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Exceptions\MemberNotFoundException;
use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateMemberServiceTest extends TestCase
{
    private MemberRepositoryInterface $repository;

    private UpdateMemberService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MemberRepositoryInterface::class);
        $this->service = new UpdateMemberService($this->repository);
    }

    public function test_can_update_member()
    {
        $existingMember = new Member(
            id: 1,
            name: MemberFactory::create('John', 'Doe')->getName(),
            dob: null,
            gender: null,
            maritalStatus: null,
            phone: null,
            position: null,
            employmentStatus: null,
            employmentNotes: null,
            householdId: null
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingMember);

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Member $member) {
                return $member;
            });

        $dto = new UpdateMemberDTO(
            id: 1,
            firstName: 'Jane',
            lastName: 'Smith',
            middleName: 'Ann',
            dob: '1995-05-05',
            gender: 'female',
            maritalStatus: 'married',
            phone: '0712345678',
            position: 'Leader',
            employmentStatus: 'employed',
            employmentNotes: 'Manager',
            householdId: 2
        );

        $updatedMember = $this->service->execute($dto);

        $this->assertEquals('Jane', $updatedMember->getName()->getFirstName());
        $this->assertEquals('Smith', $updatedMember->getName()->getLastName());
        $this->assertEquals('Ann', $updatedMember->getName()->getMiddleName());
        $this->assertEquals('1995-05-05', $updatedMember->getDob()->getValue());
    }

    public function test_throws_exception_if_member_not_found()
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $dto = new UpdateMemberDTO(
            id: 99,
            firstName: 'Jane',
            lastName: 'Smith',
            middleName: null,
            dob: null,
            gender: null,
            maritalStatus: null,
            phone: null,
            position: null,
            employmentStatus: null,
            employmentNotes: null,
            householdId: null
        );

        $this->expectException(MemberNotFoundException::class);
        $this->service->execute($dto);
    }
}
