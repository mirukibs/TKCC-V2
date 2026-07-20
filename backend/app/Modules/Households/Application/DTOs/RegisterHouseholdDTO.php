<?php

namespace App\Modules\Households\Application\DTOs;

class RegisterHouseholdDTO
{
    public readonly string $name;

    public readonly int $communityId;

    public readonly ?int $leaderId;

    public readonly string $ownership;

    public function __construct(
        string $name,
        int $communityId,
        ?int $leaderId,
        string $ownership
    ) {
        $this->name = $name;
        $this->communityId = $communityId;
        $this->leaderId = $leaderId;
        $this->ownership = $ownership;
    }
}
