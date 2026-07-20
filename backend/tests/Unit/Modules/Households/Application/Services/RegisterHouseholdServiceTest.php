<?php

namespace Tests\Unit\Modules\Households\Application\Services;

use App\Modules\Households\Application\DTOs\RegisterHouseholdDTO;
use App\Modules\Households\Application\Services\RegisterHouseholdService;
use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RegisterHouseholdServiceTest extends TestCase
{
    private HouseholdRepositoryInterface $repository;

    private RegisterHouseholdService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(HouseholdRepositoryInterface::class);
        $this->service = new RegisterHouseholdService($this->repository);
    }

    public function test_can_register_household()
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Household $household) {
                // Simulate saving by returning a household with an ID
                return new Household(
                    id: 1,
                    name: $household->getName(),
                    communityId: $household->getCommunityId(),
                    leaderId: $household->getLeaderId(),
                    ownership: $household->getOwnership()
                );
            });

        $dto = new RegisterHouseholdDTO(
            name: 'New Name',
            communityId: 1,
            leaderId: null,
            ownership: 'owned'
        );

        $household = $this->service->execute($dto);

        $this->assertEquals(1, $household->getId());
        $this->assertEquals('New Name', $household->getName());
        $this->assertEquals(1, $household->getCommunityId());
        $this->assertNull($household->getLeaderId());
        $this->assertEquals(OwnershipType::OWNED, $household->getOwnership());
    }
}
