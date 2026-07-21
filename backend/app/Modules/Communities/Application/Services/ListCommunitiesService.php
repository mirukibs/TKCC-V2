<?php

namespace App\Modules\Communities\Application\Services;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;

class ListCommunitiesService
{
    public function __construct(
        private CommunityRepositoryInterface $repository
    ) {}

    /**
     * @return Community[]
     */
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
