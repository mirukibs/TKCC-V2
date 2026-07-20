<?php

namespace App\Modules\Households\Application\Services;

use App\Modules\Households\Application\DTOs\UpdateHouseholdDTO;
use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Exceptions\HouseholdNotFoundException;
use App\Modules\Households\Domain\Factories\HouseholdFactory;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;

class UpdateHouseholdService
{
    public function __construct(
        private HouseholdRepositoryInterface $repository
    ) {}

    public function execute(UpdateHouseholdDTO $dto): Household
    {
        $existingHousehold = $this->repository->findById($dto->id);

        if (! $existingHousehold) {
            throw new HouseholdNotFoundException("Household with ID {$dto->id} not found.");
        }

        $household = HouseholdFactory::create(
            name: $dto->name,
            communityId: $dto->communityId,
            leaderId: $dto->leaderId,
            ownership: $dto->ownership,
            id: $existingHousehold->getId()
        );

        return $this->repository->save($household);
    }
}
