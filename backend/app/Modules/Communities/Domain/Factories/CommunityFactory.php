<?php

namespace App\Modules\Communities\Domain\Factories;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;

class CommunityFactory
{
    public function create(string $name, int $zoneId): Community
    {
        return new Community(null, new Name($name), $zoneId);
    }

    public function reconstitute(int $id, string $name, int $zoneId): Community
    {
        return new Community($id, new Name($name), $zoneId);
    }
}
