<?php

namespace Tests\Unit\Modules\Communities\Infrastructure\Repositories;

use App\Modules\Communities\Domain\Factories\CommunityFactory;
use App\Modules\Communities\Infrastructure\Models\CommunityModel;
use App\Modules\Communities\Infrastructure\Repositories\EloquentCommunityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommunityRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentCommunityRepository $repository;

    private CommunityFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure zones exist since community_id has a foreign key to zones table
        DB::table('zones')->insert([
            ['id' => 1, 'name' => 'Zone 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Zone 2', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->factory = new CommunityFactory;
        $this->repository = new EloquentCommunityRepository($this->factory);
    }

    public function test_can_save_and_find_community()
    {
        $community = $this->factory->create('St. Peter', 1);
        $saved = $this->repository->save($community);

        $this->assertNotNull($saved->getId());
        $this->assertEquals('St. Peter', $saved->getName());
        $this->assertEquals(1, $saved->getZoneId());

        $found = $this->repository->findById($saved->getId());
        $this->assertNotNull($found);
        $this->assertEquals('St. Peter', $found->getName());
    }

    public function test_can_find_all_communities()
    {
        CommunityModel::create(['name' => 'Com 1', 'zone_id' => 1]);
        CommunityModel::create(['name' => 'Com 2', 'zone_id' => 2]);

        $all = $this->repository->findAll();
        $this->assertCount(2, $all);
        $this->assertEquals('Com 1', $all[0]->getName());
        $this->assertEquals('Com 2', $all[1]->getName());
    }

    public function test_can_update_community()
    {
        $saved = $this->repository->save($this->factory->create('Old', 1));

        $updatedCommunity = $this->factory->reconstitute($saved->getId(), 'New', 2);
        $this->repository->save($updatedCommunity);

        $found = $this->repository->findById($saved->getId());
        $this->assertEquals('New', $found->getName());
        $this->assertEquals(2, $found->getZoneId());
    }

    public function test_can_delete_community()
    {
        $saved = $this->repository->save($this->factory->create('To Delete', 1));
        $this->assertTrue($this->repository->delete($saved->getId()));
        $this->assertNull($this->repository->findById($saved->getId()));
    }
}
