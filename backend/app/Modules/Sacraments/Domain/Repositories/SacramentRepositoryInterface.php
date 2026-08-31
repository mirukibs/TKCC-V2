<?php

namespace App\Modules\Sacraments\Domain\Repositories;

use App\Modules\Sacraments\Domain\Entities\Sacrament;
use Illuminate\Pagination\LengthAwarePaginator;

interface SacramentRepositoryInterface
{
    public function findById(int $id): ?Sacrament;

    public function findByMemberId(int $memberId): ?Sacrament;

    public function findAll(int $page = 1, int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function save(Sacrament $sacrament): Sacrament;

    public function delete(int $id): bool;
}
