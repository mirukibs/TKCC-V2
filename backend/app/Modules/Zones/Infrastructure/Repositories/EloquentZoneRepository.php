<?php

namespace App\Modules\Zones\Infrastructure\Repositories;

use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Factories\ZoneFactory;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use App\Modules\Zones\Infrastructure\Models\ZoneModel;

class EloquentZoneRepository implements ZoneRepositoryInterface
{
    public function __construct(private ZoneFactory $factory) {}

    public function findById(int $id): ?Zone
    {
        $model = ZoneModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        return ZoneModel::all()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Zone $zone): Zone
    {
        $model = ZoneModel::updateOrCreate(
            ['id' => $zone->getId()],
            [
                'name' => $zone->getName(),
            ]
        );

        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        $model = ZoneModel::find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    private function toEntity(ZoneModel $model): Zone
    {
        return $this->factory->reconstitute(
            $model->id,
            $model->name
        );
    }
}
