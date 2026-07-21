<?php

namespace Tests\Unit\Modules\Communities\Application\Services;

use App\Modules\Communities\Application\DTOs\RegisterCommunityDTO;
use App\Modules\Communities\Application\DTOs\UpdateCommunityDTO;
use App\Modules\Communities\Application\Services\DeleteCommunityService;
use App\Modules\Communities\Application\Services\GetCommunityService;
use App\Modules\Communities\Application\Services\ListCommunitiesService;
use App\Modules\Communities\Application\Services\RegisterCommunityService;
use App\Modules\Communities\Application\Services\UpdateCommunityService;
use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Exceptions\CommunityNotFoundException;
use App\Modules\Communities\Domain\Factories\CommunityFactory;
use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class CommunityServicesTest extends TestCase
{
    private $repository;

    private $factory;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CommunityRepositoryInterface::class);
        $this->factory = new CommunityFactory;
    }

    public function test_register_community_service_creates_and_saves_community()
    {
        $dto = new RegisterCommunityDTO('St. Mary', 1);
        $community = new Community(1, new Name('St. Mary'), 1);

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturn($community);

        $service = new RegisterCommunityService($this->repository, $this->factory);
        $result = $service->execute($dto);

        $this->assertInstanceOf(Community::class, $result);
        $this->assertEquals('St. Mary', $result->getName());
        $this->assertEquals(1, $result->getZoneId());
    }

    public function test_get_community_service_returns_community_if_found()
    {
        $community = new Community(1, new Name('St. Mary'), 1);
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($community);

        $service = new GetCommunityService($this->repository);
        $result = $service->execute(1);

        $this->assertEquals($community, $result);
    }

    public function test_get_community_service_throws_if_not_found()
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $service = new GetCommunityService($this->repository);

        $this->expectException(CommunityNotFoundException::class);
        $service->execute(999);
    }

    public function test_list_communities_service_returns_array()
    {
        $communities = [new Community(1, new Name('C1'), 1), new Community(2, new Name('C2'), 2)];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($communities);

        $service = new ListCommunitiesService($this->repository);
        $result = $service->execute();

        $this->assertCount(2, $result);
        $this->assertEquals($communities, $result);
    }

    public function test_update_community_service_updates_and_saves()
    {
        $community = new Community(1, new Name('Old Name'), 1);
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($community);

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(fn ($c) => $c);

        $dto = new UpdateCommunityDTO('New Name', 2);
        $service = new UpdateCommunityService($this->repository, $this->factory);
        $result = $service->execute(1, $dto);

        $this->assertEquals('New Name', $result->getName());
        $this->assertEquals(2, $result->getZoneId());
    }

    public function test_delete_community_service_deletes()
    {
        $community = new Community(1, new Name('Old Name'), 1);
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($community);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $service = new DeleteCommunityService($this->repository);
        $result = $service->execute(1);

        $this->assertTrue($result);
    }
}
