<?php

namespace App\Modules\Households\Application\Services;

use App\Modules\Households\Application\DTOs\RegisterHouseholdDTO;
use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Factories\HouseholdFactory;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;

class RegisterHouseholdService
{
    private HouseholdRepositoryInterface $repository;

    public function __construct(
        HouseholdRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function execute(RegisterHouseholdDTO $dto): Household
    {
        $household = HouseholdFactory::create(
            $dto->name,
            $dto->communityId,
            $dto->leaderId,
            $dto->ownership
        );

        return $this->repository->save($household);
    }
}
