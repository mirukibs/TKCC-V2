<?php

namespace App\Modules\Communities\Application\DTOs;

class UpdateCommunityDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $zoneId
    ) {}
}
