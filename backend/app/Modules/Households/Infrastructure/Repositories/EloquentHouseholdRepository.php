<?php

namespace App\Modules\Households\Infrastructure\Repositories;

use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use App\Modules\Households\Infrastructure\Models\HouseholdModel;

class EloquentHouseholdRepository implements HouseholdRepositoryInterface
{
    public function save(Household $household): Household
    {
        $model = HouseholdModel::updateOrCreate(
            ['id' => $household->getId()],
            [
                'name' => $household->getName(),
                'community_id' => $household->getCommunityId(),
                'leader_id' => $household->getLeaderId(),
                'ownership' => $household->getOwnership()->value,
            ]
        );

        return $this->toDomain($model);
    }

    public function findById(int $id): ?Household
    {
        $model = HouseholdModel::find($id);

        if (! $model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findAll(array $filters = []): array
    {
        $query = HouseholdModel::query();

        if (! empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';
            $query->where('name', 'LIKE', $search);
        }

        if (! empty($filters['ownership'])) {
            $query->where('ownership', $filters['ownership']);
        }

        return $query->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->toArray();
    }

    public function delete(int $id): bool
    {
        return HouseholdModel::destroy($id) > 0;
    }

    private function toDomain(HouseholdModel $model): Household
    {
        return new Household(
            $model->id,
            $model->name,
            $model->community_id,
            $model->leader_id,
            OwnershipType::from($model->ownership)
        );
    }
}
