<?php

namespace App\Modules\Zones\Application\Services;

use App\Modules\Zones\Application\DTOs\RegisterZoneDTO;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Factories\ZoneFactory;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;

class RegisterZoneService
{
    public function __construct(
        private ZoneRepositoryInterface $repository,
        private ZoneFactory $factory
    ) {}

    public function execute(RegisterZoneDTO $dto): Zone
    {
        $zone = $this->factory->create($dto->toArray());
        return $this->repository->save($zone);
    }
}
