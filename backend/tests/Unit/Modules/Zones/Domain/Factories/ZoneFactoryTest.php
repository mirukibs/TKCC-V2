<?php

namespace Tests\Unit\Modules\Zones\Domain\Factories;

use App\Modules\SharedKernel\Domain\Exceptions\InvalidNameException;
use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Domain\Factories\ZoneFactory;
use PHPUnit\Framework\TestCase;

class ZoneFactoryTest extends TestCase
{
    public function test_can_create_zone()
    {
        $factory = new ZoneFactory;
        $zone = $factory->create(['name' => 'New Zone']);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertNull($zone->getId());
        $this->assertEquals('New Zone', $zone->getName());
    }

    public function test_can_reconstitute_zone()
    {
        $factory = new ZoneFactory;
        $zone = $factory->reconstitute(1, 'Existing Zone');
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertEquals(1, $zone->getId());
        $this->assertEquals('Existing Zone', $zone->getName());
    }

    public function test_throws_exception_for_invalid_name()
    {
        $this->expectException(InvalidNameException::class);
        $factory = new ZoneFactory;
        $factory->create(['name' => '']);
    }
}
