<?php

namespace Tests\Unit\Modules\Zones\Application\Services;

use App\Modules\Zones\Application\Services\DeleteZoneService;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteZoneServiceTest extends TestCase
{
    public function test_can_delete_zone()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(new \App\Modules\Zones\Domain\Entities\Zone(1, new \App\Modules\SharedKernel\Domain\ValueObjects\Name('Zone 1')));

        $repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $service = new DeleteZoneService($repository);
        $service->execute(1);
    }

    public function test_throws_exception_when_zone_not_found()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);

        $repository->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $service = new DeleteZoneService($repository);

        $this->expectException(ZoneNotFoundException::class);
        $service->execute(99);
    }
}
