<?php

namespace Tests\Unit\Modules\Communities\Domain\Entities;

use App\Modules\Communities\Domain\Entities\Community;
use App\Modules\Communities\Domain\Exceptions\InvalidCommunityDataException;
use App\Modules\SharedKernel\Domain\Exceptions\InvalidNameException;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use PHPUnit\Framework\TestCase;

class CommunityTest extends TestCase
{
    public function test_can_instantiate_community()
    {
        $community = new Community(1, new Name('St. Joseph'), 2);

        $this->assertEquals(1, $community->getId());
        $this->assertEquals('St. Joseph', $community->getName());
        $this->assertEquals(2, $community->getZoneId());
    }

    public function test_cannot_instantiate_with_empty_name()
    {
        $this->expectException(InvalidNameException::class);
        new Community(1, new Name('   '), 2);
    }

    public function test_cannot_instantiate_with_invalid_zone()
    {
        $this->expectException(InvalidCommunityDataException::class);
        new Community(1, new Name('St. Joseph'), 0);
    }

    public function test_to_array_returns_expected_structure()
    {
        $community = new Community(1, new Name('St. Joseph'), 2);
        $array = $community->toArray();

        $this->assertEquals([
            'id' => 1,
            'name' => 'St. Joseph',
            'zone_id' => 2,
        ], $array);
    }
}
