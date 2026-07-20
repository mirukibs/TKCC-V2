<?php

namespace App\Modules\Households\Application\DTOs;

class UpdateHouseholdDTO
{
    public readonly int $id;

    public readonly string $name;

    public readonly int $communityId;

    public readonly ?int $leaderId;

    public readonly string $ownership;

    public function __construct(
        int $id,
        string $name,
        int $communityId,
        ?int $leaderId,
        string $ownership
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->communityId = $communityId;
        $this->leaderId = $leaderId;
        $this->ownership = $ownership;
    }
}
