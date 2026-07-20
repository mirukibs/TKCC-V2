<?php

namespace Tests\Unit\Modules\Members\Application\Services;

use App\Modules\Members\Application\Services\GetMemberService;
use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetMemberServiceTest extends TestCase
{
    private MemberRepositoryInterface $repository;

    private GetMemberService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MemberRepositoryInterface::class);
        $this->service = new GetMemberService($this->repository);
    }

    public function test_can_get_member()
    {
        $member = new Member(
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
            ->willReturn($member);

        $found = $this->service->execute(1);

        $this->assertEquals(1, $found->getId());
        $this->assertEquals('John', $found->getName()->getFirstName());
    }

    public function test_returns_null_if_member_not_found()
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $result = $this->service->execute(99);
        $this->assertNull($result);
    }
}
