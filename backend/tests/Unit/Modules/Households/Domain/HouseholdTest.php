<?php

namespace Tests\Unit\Modules\Households\Domain;

use App\Modules\Households\Domain\Entities\Household;
use App\Modules\Households\Domain\Enums\OwnershipType;
use PHPUnit\Framework\TestCase;

class HouseholdTest extends TestCase
{
    public function test_can_create_household()
    {
        $household = new Household(
            1,
            'Test Family',
            10,
            5,
            OwnershipType::OWNED
        );

        $this->assertEquals(1, $household->getId());
        $this->assertEquals('Test Family', $household->getName());
        $this->assertEquals(10, $household->getCommunityId());
        $this->assertEquals(5, $household->getLeaderId());
        $this->assertEquals(OwnershipType::OWNED, $household->getOwnership());

        $array = $household->toArray();
        $this->assertEquals('owned', $array['ownership']);
    }
}
