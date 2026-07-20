<?php

namespace Tests\Unit\Modules\Households\Application\Services;

use App\Modules\Households\Application\Services\GetHouseholdService;
use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;
use App\Modules\Households\Domain\Exceptions\HouseholdNotFoundException;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetHouseholdServiceTest extends TestCase
{
    private HouseholdRepositoryInterface $repository;

    private GetHouseholdService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(HouseholdRepositoryInterface::class);
        $this->service = new GetHouseholdService($this->repository);
    }

    public function test_can_get_household()
    {
        $household = new Household(
            id: 1,
            name: 'Old Name',
            communityId: 1,
            leaderId: null,
            ownership: OwnershipType::OWNED
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($household);

        $found = $this->service->execute(1);

        $this->assertEquals(1, $found->getId());
        $this->assertEquals('Old Name', $found->getName());
    }

    public function test_throws_exception_if_household_not_found()
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $this->expectException(HouseholdNotFoundException::class);
        $this->service->execute(99);
    }
}
