<?php

namespace App\Modules\Communities\Application\Services;

use App\Modules\Communities\Domain\Exceptions\CommunityNotFoundException;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;

class DeleteCommunityService
{
    public function __construct(
        private CommunityRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        $community = $this->repository->findById($id);

        if (! $community) {
            throw new CommunityNotFoundException('Community not found.');
        }

        return $this->repository->delete($id);
    }
}
