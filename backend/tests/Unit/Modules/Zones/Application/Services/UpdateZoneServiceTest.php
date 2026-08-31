<?php

namespace Tests\Unit\Modules\Zones\Application\Services;

use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use App\Modules\Zones\Application\DTOs\UpdateZoneDTO;
use App\Modules\Zones\Application\Services\UpdateZoneService;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Domain\Factories\ZoneFactory;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateZoneServiceTest extends TestCase
{
    public function test_can_update_zone()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);
        $dto = new UpdateZoneDTO(1, 'Updated Zone');

        $zone = new Zone(1, new Name('Old Zone'));
        $updatedZone = new Zone(1, new Name('Updated Zone'));

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($zone);

        $repository->expects($this->once())
            ->method('save')
            ->willReturn($updatedZone);

        $factory = new ZoneFactory;
        $service = new UpdateZoneService($repository, $factory);
        $result = $service->execute($dto);

        $this->assertEquals('Updated Zone', $result->getName());
    }

    public function test_throws_exception_when_zone_not_found()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);
        $dto = new UpdateZoneDTO(99, 'Updated Zone');

        $repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $factory = new ZoneFactory;
        $service = new UpdateZoneService($repository, $factory);

        $this->expectException(ZoneNotFoundException::class);
        $service->execute($dto);
    }
}
