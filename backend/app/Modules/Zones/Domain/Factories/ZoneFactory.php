<?php

namespace App\Modules\Zones\Domain\Factories;

use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use App\Modules\Zones\Domain\Entities\Zone;

class ZoneFactory
{
    public function create(array $data): Zone
    {
        return new Zone(
            null,
            new Name($data['name'])
        );
    }

    public function reconstitute(int $id, string $name): Zone
    {
        return new Zone(
            $id,
            new Name($name)
        );
    }
}
