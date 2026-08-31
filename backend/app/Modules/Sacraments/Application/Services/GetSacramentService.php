<?php

namespace App\Modules\Sacraments\Application\Services;

use App\Modules\Sacraments\Domain\Entities\Sacrament;
use App\Modules\Sacraments\Domain\Exceptions\SacramentNotFoundException;
use App\Modules\Sacraments\Domain\Repositories\SacramentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetSacramentService
{
    public function __construct(
        private SacramentRepositoryInterface $repository
    ) {}

    public function getAll(int $page = 1, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->findAll($page, $perPage, $filters);
    }

    public function getById(int $id): Sacrament
    {
        $sacrament = $this->repository->findById($id);

        if (! $sacrament) {
            throw new SacramentNotFoundException;
        }

        return $sacrament;
    }

    public function getByMemberId(int $memberId): Sacrament
    {
        $sacrament = $this->repository->findByMemberId($memberId);

        if (! $sacrament) {
            throw new SacramentNotFoundException("Sacrament for member ID {$memberId} not found.");
        }

        return $sacrament;
    }
}
