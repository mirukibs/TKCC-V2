<?php

namespace App\Modules\Zones\Application\Services;

use App\Modules\Zones\Application\DTOs\UpdateZoneDTO;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Domain\Factories\ZoneFactory;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;

class UpdateZoneService
{
    public function __construct(
        private ZoneRepositoryInterface $repository,
        private ZoneFactory $factory
    ) {}

    public function execute(UpdateZoneDTO $dto): Zone
    {
        $zone = $this->repository->findById($dto->id);

        if (!$zone) {
            throw new ZoneNotFoundException("Zone with ID {$dto->id} not found.");
        }

        $updatedZone = $this->factory->reconstitute(
            $dto->id,
            $dto->name
        );

        return $this->repository->save($updatedZone);
    }
}
