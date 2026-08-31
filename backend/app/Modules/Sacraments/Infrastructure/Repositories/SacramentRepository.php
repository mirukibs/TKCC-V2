<?php

namespace App\Modules\Sacraments\Infrastructure\Repositories;

use App\Modules\Sacraments\Domain\Entities\Sacrament;
use App\Modules\Sacraments\Domain\Enums\BaptismStatus;
use App\Modules\Sacraments\Domain\Enums\ConfirmationStatus;
use App\Modules\Sacraments\Domain\Enums\MarriageStatus;
use App\Modules\Sacraments\Domain\Repositories\SacramentRepositoryInterface;
use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
use DateTimeImmutable;
use Illuminate\Pagination\LengthAwarePaginator;

class SacramentRepository implements SacramentRepositoryInterface
{
    public function findById(int $id): ?Sacrament
    {
        $model = SacramentModel::find($id);
        if (! $model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByMemberId(int $memberId): ?Sacrament
    {
        $model = SacramentModel::where('member_id', $memberId)->first();
        if (! $model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(int $page = 1, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = SacramentModel::query()->with('member');

        if (isset($filters['search'])) {
            $query->whereHas('member', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('last_name', 'like', '%'.$filters['search'].'%');
            });
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (isset($filters['baptism_status'])) {
            $query->where('baptism_status', $filters['baptism_status']);
        }

        if (isset($filters['confirmation_status'])) {
            $query->where('confirmation_status', $filters['confirmation_status']);
        }

        if (isset($filters['marriage_status'])) {
            $query->where('marriage_status', $filters['marriage_status']);
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $paginator->getCollection()->transform(function ($model) {
            return $this->toEntity($model);
        });

        return $paginator;
    }

    public function save(Sacrament $sacrament): Sacrament
    {
        $model = SacramentModel::updateOrCreate(
            ['member_id' => $sacrament->getMemberId()],
            [
                'baptism_status' => $sacrament->getBaptismStatus()->value,
                'baptism_date' => $sacrament->getBaptismDate()?->format('Y-m-d'),
                'baptism_place' => $sacrament->getBaptismPlace(),
                'confirmation_status' => $sacrament->getConfirmationStatus()->value,
                'confirmation_date' => $sacrament->getConfirmationDate()?->format('Y-m-d'),
                'confirmation_place' => $sacrament->getConfirmationPlace(),
                'marriage_status' => $sacrament->getMarriageStatus()->value,
                'marriage_date' => $sacrament->getMarriageDate()?->format('Y-m-d'),
                'marriage_place' => $sacrament->getMarriagePlace(),
            ]
        );

        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return SacramentModel::destroy($id) > 0;
    }

    private function toEntity(SacramentModel $model): Sacrament
    {
        return new Sacrament(
            $model->id,
            $model->member_id,
            BaptismStatus::from($model->baptism_status),
            $model->baptism_date ? new DateTimeImmutable($model->baptism_date->format('Y-m-d')) : null,
            $model->baptism_place,
            ConfirmationStatus::from($model->confirmation_status),
            $model->confirmation_date ? new DateTimeImmutable($model->confirmation_date->format('Y-m-d')) : null,
            $model->confirmation_place,
            MarriageStatus::from($model->marriage_status),
            $model->marriage_date ? new DateTimeImmutable($model->marriage_date->format('Y-m-d')) : null,
            $model->marriage_place,
            $model->created_at ? new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')) : null,
            $model->updated_at ? new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')) : null
        );
    }
}
