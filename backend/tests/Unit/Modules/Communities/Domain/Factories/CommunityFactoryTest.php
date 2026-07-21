<?php

namespace Tests\Unit\Modules\Communities\Domain\Factories;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Factories\CommunityFactory;
use PHPUnit\Framework\TestCase;

class CommunityFactoryTest extends TestCase
{
    public function test_create_returns_new_community_without_id()
    {
        $factory = new CommunityFactory;
        $community = $factory->create('St. Peter', 5);

        $this->assertInstanceOf(Community::class, $community);
        $this->assertNull($community->getId());
        $this->assertEquals('St. Peter', $community->getName());
        $this->assertEquals(5, $community->getZoneId());
    }

    public function test_reconstitute_returns_community_with_id()
    {
        $factory = new CommunityFactory;
        $community = $factory->reconstitute(10, 'St. Peter', 5);

        $this->assertInstanceOf(Community::class, $community);
        $this->assertEquals(10, $community->getId());
        $this->assertEquals('St. Peter', $community->getName());
        $this->assertEquals(5, $community->getZoneId());
    }
}
