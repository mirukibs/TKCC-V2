<?php

namespace App\Modules\Communities\Application\Services;

use App\Modules\Communities\Application\DTOs\RegisterCommunityDTO;
use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Factories\CommunityFactory;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;

class RegisterCommunityService
{
    public function __construct(
        private CommunityRepositoryInterface $repository,
        private CommunityFactory $factory
    ) {}

    public function execute(RegisterCommunityDTO $dto): Community
    {
        $community = $this->factory->create(
            $dto->name,
            $dto->zoneId
        );

        return $this->repository->save($community);
    }
}
