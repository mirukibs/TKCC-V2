<?php

namespace Tests\Unit\Modules\Zones\Application\Services;

use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use App\Modules\Zones\Application\Services\GetZoneService;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetZoneServiceTest extends TestCase
{
    public function test_can_get_zone()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);
        $zone = new Zone(1, new Name('Zone 1'));

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($zone);

        $service = new GetZoneService($repository);
        $result = $service->execute(1);

        $this->assertSame($zone, $result);
    }

    public function test_throws_exception_if_zone_not_found()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);

        $repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $service = new GetZoneService($repository);

        $this->expectException(ZoneNotFoundException::class);
        $service->execute(99);
    }
}
