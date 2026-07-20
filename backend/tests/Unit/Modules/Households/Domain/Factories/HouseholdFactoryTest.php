<?php

namespace Tests\Unit\Modules\Households\Domain\Factories;

use App\Modules\Households\Domain\Enums\OwnershipType;
use App\Modules\Households\Domain\Factories\HouseholdFactory;
use PHPUnit\Framework\TestCase;

class HouseholdFactoryTest extends TestCase
{
    public function test_can_create_household_with_all_fields()
    {
        $household = HouseholdFactory::create(
            name: 'Smith Residence',
            communityId: 10,
            leaderId: 5,
            ownership: 'owned'
        );

        $this->assertEquals('Smith Residence', $household->getName());
        $this->assertEquals(10, $household->getCommunityId());
        $this->assertEquals(5, $household->getLeaderId());
        $this->assertEquals(OwnershipType::OWNED, $household->getOwnership());
    }

    public function test_can_create_household_with_minimal_fields()
    {
        $household = HouseholdFactory::create(
            name: 'Doe Residence',
            communityId: 20,
            leaderId: null,
            ownership: 'rented'
        );

        $this->assertEquals('Doe Residence', $household->getName());
        $this->assertEquals(20, $household->getCommunityId());
        $this->assertNull($household->getLeaderId());
        $this->assertEquals(OwnershipType::RENTED, $household->getOwnership());
    }
}
