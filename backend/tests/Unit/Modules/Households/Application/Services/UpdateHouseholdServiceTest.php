<?php

namespace Tests\Unit\Modules\Households\Application\Services;

use App\Modules\Households\Application\DTOs\UpdateHouseholdDTO;
use App\Modules\Households\Application\Services\UpdateHouseholdService;
use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;
use App\Modules\Households\Domain\Exceptions\HouseholdNotFoundException;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateHouseholdServiceTest extends TestCase
{
    private HouseholdRepositoryInterface $repository;

    private UpdateHouseholdService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(HouseholdRepositoryInterface::class);
        $this->service = new UpdateHouseholdService($this->repository);
    }

    public function test_can_update_household()
    {
        $existingHousehold = new Household(
            id: 1,
            name: 'Old Name',
            communityId: 1,
            leaderId: null,
            ownership: OwnershipType::OWNED
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingHousehold);

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Household $household) {
                return $household;
            });

        $dto = new UpdateHouseholdDTO(
            id: 1,
            name: 'New Name',
            communityId: 2,
            leaderId: 5,
            ownership: 'rented'
        );

        $updatedHousehold = $this->service->execute($dto);

        $this->assertEquals(1, $updatedHousehold->getId());
        $this->assertEquals('New Name', $updatedHousehold->getName());
        $this->assertEquals(2, $updatedHousehold->getCommunityId());
        $this->assertEquals(5, $updatedHousehold->getLeaderId());
        $this->assertEquals(OwnershipType::RENTED, $updatedHousehold->getOwnership());
    }

    public function test_throws_exception_if_household_not_found()
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $dto = new UpdateHouseholdDTO(
            id: 99,
            name: 'New Name',
            communityId: 2,
            leaderId: null,
            ownership: 'rented'
        );

        $this->expectException(HouseholdNotFoundException::class);
        $this->service->execute($dto);
    }
}
