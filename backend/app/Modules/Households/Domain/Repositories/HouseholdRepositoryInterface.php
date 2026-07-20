<?php

namespace App\Modules\Households\Domain\Repositories;

use App\Modules\Households\Domain\Entities\Household;

interface HouseholdRepositoryInterface
{
    public function save(Household $household): Household;

    public function findById(int $id): ?Household;

    public function findAll(array $filters = []): array;

    public function delete(int $id): bool;
}
