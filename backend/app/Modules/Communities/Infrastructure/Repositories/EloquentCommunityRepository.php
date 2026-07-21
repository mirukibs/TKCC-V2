<?php

namespace App\Modules\Communities\Infrastructure\Repositories;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Factories\CommunityFactory;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;
use App\Modules\Communities\Infrastructure\Models\CommunityModel;

class EloquentCommunityRepository implements CommunityRepositoryInterface
{
    public function __construct(
        private CommunityFactory $factory
    ) {}

    public function findById(int $id): ?Community
    {
        $model = CommunityModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        return CommunityModel::all()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Community $community): Community
    {
        $model = CommunityModel::updateOrCreate(
            ['id' => $community->getId()],
            [
                'name' => $community->getName(),
                'zone_id' => $community->getZoneId(),
            ]
        );

        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        $model = CommunityModel::find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    private function toEntity(CommunityModel $model): Community
    {
        return $this->factory->reconstitute(
            $model->id,
            $model->name,
            $model->zone_id
        );
    }
}
