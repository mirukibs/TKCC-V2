<?php

namespace App\Modules\Zones\Application\Services;

use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;

class ListZonesService
{
    public function __construct(private ZoneRepositoryInterface $repository) {}

    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
