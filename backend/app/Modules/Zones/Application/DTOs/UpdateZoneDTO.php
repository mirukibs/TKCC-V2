<?php

namespace App\Modules\Zones\Application\DTOs;

class UpdateZoneDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
