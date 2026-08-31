<?php

namespace Tests\Unit\Modules\Zones\Application\Services;

use App\Modules\Zones\Application\DTOs\RegisterZoneDTO;
use App\Modules\Zones\Application\Services\RegisterZoneService;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;

class RegisterZoneServiceTest extends TestCase
{
    public function test_can_register_zone()
    {
        $repository = $this->createMock(ZoneRepositoryInterface::class);
        $dto = new RegisterZoneDTO('New Zone');
        
        $zone = new Zone(1, new Name('New Zone'));

        $repository->expects($this->once())
            ->method('save')
            ->willReturn($zone);

        $factory = new \App\Modules\Zones\Domain\Factories\ZoneFactory();
        $service = new RegisterZoneService($repository, $factory);
        $result = $service->execute($dto);

        $this->assertSame($zone, $result);
    }
}
