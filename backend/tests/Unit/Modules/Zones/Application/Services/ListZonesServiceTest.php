<?php

namespace Tests\Unit\Modules\Zones\Application\Services;

use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use App\Modules\Zones\Application\Services\ListZonesService;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListZonesServiceTest extends TestCase
{
    public function test_can_list_zones()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);
        $zones = [
            new Zone(1, new Name('Zone 1')),
            new Zone(2, new Name('Zone 2')),
        ];

        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn($zones);

        $service = new ListZonesService($repository);
        $result = $service->execute();

        $this->assertCount(2, $result);
        $this->assertSame($zones, $result);
    }
}
