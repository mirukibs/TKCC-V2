<?php

namespace Tests\Unit\Modules\Households\Infrastructure\Repositories;

use App\Modules\Households\Domain\Factories\HouseholdFactory;
use App\Modules\Households\Infrastructure\Repositories\EloquentHouseholdRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HouseholdRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentHouseholdRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentHouseholdRepository;

        DB::table('zones')->insert([
            ['id' => 1, 'name' => 'Zone 1'],
            ['id' => 2, 'name' => 'Zone 2'],
        ]);

        DB::table('communities')->insert([
            ['id' => 10, 'name' => 'Community 10', 'zone_id' => 1],
            ['id' => 20, 'name' => 'Community 20', 'zone_id' => 2],
        ]);
    }

    public function test_can_save_and_find_by_id()
    {
        $household = HouseholdFactory::create(
            name: 'Smith Residence',
            communityId: 10,
            leaderId: null,
            ownership: 'owned'
        );

        $savedHousehold = $this->repository->save($household);
        $this->assertNotNull($savedHousehold->getId());

        $foundHousehold = $this->repository->findById($savedHousehold->getId());
        $this->assertNotNull($foundHousehold);
        $this->assertEquals('Smith Residence', $foundHousehold->getName());
        $this->assertEquals(10, $foundHousehold->getCommunityId());
    }

    public function test_can_find_all_and_filter()
    {
        $household1 = HouseholdFactory::create(name: 'Smith Residence', communityId: 10, leaderId: null, ownership: 'owned');
        $household2 = HouseholdFactory::create(name: 'Doe Residence', communityId: 20, leaderId: null, ownership: 'rented');

        $this->repository->save($household1);
        $this->repository->save($household2);

        $results = $this->repository->findAll(['search' => 'Doe']);
        $this->assertCount(1, $results);
        $this->assertEquals('Doe Residence', $results[0]->getName());

        $allResults = $this->repository->findAll([]);
        $this->assertCount(2, $allResults);
    }

    public function test_can_delete()
    {
        $household = HouseholdFactory::create(name: 'Smith Residence', communityId: 10, leaderId: null, ownership: 'owned');
        $savedHousehold = $this->repository->save($household);

        $this->assertNotNull($this->repository->findById($savedHousehold->getId()));

        $this->repository->delete($savedHousehold->getId());

        $this->assertNull($this->repository->findById($savedHousehold->getId()));
    }
}
