<?php

namespace App\Modules\Households\Domain\Entities;

use App\Modules\Households\Domain\Enums\OwnershipType;

class Household
{
    private ?int $id;

    private string $name;

    private int $communityId;

    private ?int $leaderId;

    private OwnershipType $ownership;

    public function __construct(
        ?int $id,
        string $name,
        int $communityId,
        ?int $leaderId,
        OwnershipType $ownership
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->communityId = $communityId;
        $this->leaderId = $leaderId;
        $this->ownership = $ownership;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCommunityId(): int
    {
        return $this->communityId;
    }

    public function getLeaderId(): ?int
    {
        return $this->leaderId;
    }

    public function getOwnership(): OwnershipType
    {
        return $this->ownership;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'community_id' => $this->communityId,
            'leader_id' => $this->leaderId,
            'ownership' => $this->ownership->value,
        ];
    }
}
