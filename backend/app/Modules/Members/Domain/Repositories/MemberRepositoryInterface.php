<?php

namespace App\Modules\Members\Domain\Repositories;

use App\Modules\Members\Domain\Entities\Member;

interface MemberRepositoryInterface
{
    public function save(Member $member): Member;

    public function findById(int $id): ?Member;

    public function findAll(array $filters = []): array;

    public function delete(int $id): bool;
}
