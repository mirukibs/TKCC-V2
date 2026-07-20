<?php

namespace App\Modules\Households\Domain\Factories;

use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;

class HouseholdFactory
{
    public static function create(
        string $name,
        int $communityId,
        ?int $leaderId,
        string $ownership,
        ?int $id = null
    ): Household {
        return new Household(
            $id,
            $name,
            $communityId,
            $leaderId,
            OwnershipType::from($ownership)
        );
    }
}
