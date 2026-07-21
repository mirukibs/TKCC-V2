<?php

namespace App\Modules\Communities\Application\Services;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Exceptions\CommunityNotFoundException;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;

class GetCommunityService
{
    public function __construct(
        private CommunityRepositoryInterface $repository
    ) {}

    public function execute(int $id): Community
    {
        $community = $this->repository->findById($id);

        if (!$community) {
            throw new CommunityNotFoundException("Community not found.");
        }

        return $community;
    }
}
