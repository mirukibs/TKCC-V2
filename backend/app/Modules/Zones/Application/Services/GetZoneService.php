<?php

namespace App\Modules\Zones\Application\Services;

use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;

class GetZoneService
{
    public function __construct(private ZoneRepositoryInterface $repository) {}

    public function execute(int $id): Zone
    {
        $zone = $this->repository->findById($id);

        if (!$zone) {
            throw new ZoneNotFoundException("Zone with ID {$id} not found.");
        }

        return $zone;
    }
}
