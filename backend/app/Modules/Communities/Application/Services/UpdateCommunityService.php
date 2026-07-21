<?php

namespace App\Modules\Communities\Application\Services;

use App\Modules\Communities\Application\DTOs\UpdateCommunityDTO;
use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Exceptions\CommunityNotFoundException;
use App\Modules\Communities\Domain\Factories\CommunityFactory;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;

class UpdateCommunityService
{
    public function __construct(
        private CommunityRepositoryInterface $repository,
        private CommunityFactory $factory
    ) {}

    public function execute(int $id, UpdateCommunityDTO $dto): Community
    {
        $community = $this->repository->findById($id);

        if (! $community) {
            throw new CommunityNotFoundException('Community not found.');
        }

        $updatedCommunity = $this->factory->reconstitute(
            $community->getId(),
            $dto->name,
            $dto->zoneId
        );

        return $this->repository->save($updatedCommunity);
    }
}
