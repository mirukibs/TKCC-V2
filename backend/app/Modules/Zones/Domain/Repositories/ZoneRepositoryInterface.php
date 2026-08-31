<?php

namespace App\Modules\Zones\Domain\Repositories;

use App\Modules\Zones\Domain\Entities\Zone;

interface ZoneRepositoryInterface
{
    public function findById(int $id): ?Zone;

    public function findAll(): array;

    public function save(Zone $zone): Zone;

    public function delete(int $id): bool;
}
