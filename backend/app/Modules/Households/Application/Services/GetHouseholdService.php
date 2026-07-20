<?php

namespace App\Modules\Households\Application\Services;

use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Exceptions\HouseholdNotFoundException;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;

class GetHouseholdService
{
    private HouseholdRepositoryInterface $repository;

    public function __construct(HouseholdRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): Household
    {
        $household = $this->repository->findById($id);

        if (! $household) {
            throw HouseholdNotFoundException::withId($id);
        }

        return $household;
    }
}
