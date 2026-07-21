<?php

namespace App\Modules\Communities\Domain\Entities;

use App\Modules\Communities\Domain\Exceptions\InvalidCommunityDataException;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;

class Community
{
    private ?int $id;

    private Name $name;

    private int $zoneId;

    public function __construct(?int $id, Name $name, int $zoneId)
    {
        $this->id = $id;
        $this->name = $name;

        if ($zoneId <= 0) {
            throw new InvalidCommunityDataException('Invalid zone ID.');
        }
        $this->zoneId = $zoneId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }

    public function getZoneId(): int
    {
        return $this->zoneId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name->getValue(),
            'zone_id' => $this->zoneId,
        ];
    }
}
