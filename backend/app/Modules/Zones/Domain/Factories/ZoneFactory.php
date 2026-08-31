<?php

namespace App\Modules\Zones\Domain\Factories;

use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;

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
