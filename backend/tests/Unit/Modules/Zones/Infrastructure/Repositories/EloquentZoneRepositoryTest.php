<?php

namespace Tests\Unit\Modules\Zones\Infrastructure\Repositories;

use App\Modules\Zones\Domain\Entities\Zone;
use App\Modules\Zones\Infrastructure\Repositories\EloquentZoneRepository;
use App\Modules\Zones\Infrastructure\Models\ZoneModel;
use App\Modules\SharedKernel\Domain\ValueObjects\Name;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentZoneRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentZoneRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $factory = new \App\Modules\Zones\Domain\Factories\ZoneFactory();
        $this->repository = new EloquentZoneRepository($factory);
    }

    public function test_can_save_zone()
    {
        $zone = new Zone(null, new Name('Test Zone'));
        
        $savedZone = $this->repository->save($zone);
        
        $this->assertNotNull($savedZone->getId());
        $this->assertEquals('Test Zone', $savedZone->getName());
        
        $this->assertDatabaseHas('zones', [
            'id' => $savedZone->getId(),
            'name' => 'Test Zone'
        ]);
    }

    public function test_can_find_zone_by_id()
    {
        $model = ZoneModel::create(['name' => 'Existing Zone']);
        
        $foundZone = $this->repository->findById($model->id);
        
        $this->assertNotNull($foundZone);
        $this->assertEquals($model->id, $foundZone->getId());
        $this->assertEquals('Existing Zone', $foundZone->getName());
    }

    public function test_find_by_id_returns_null_if_not_found()
    {
        $foundZone = $this->repository->findById(999);
        $this->assertNull($foundZone);
    }

    public function test_can_find_all_zones()
    {
        ZoneModel::create(['name' => 'Zone 1']);
        ZoneModel::create(['name' => 'Zone 2']);
        
        $zones = $this->repository->findAll();
        
        $this->assertCount(2, $zones);
        $this->assertEquals('Zone 1', $zones[0]->getName());
        $this->assertEquals('Zone 2', $zones[1]->getName());
    }

    public function test_can_delete_zone()
    {
        $model = ZoneModel::create(['name' => 'To Delete']);
        
        $result = $this->repository->delete($model->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseMissing('zones', ['id' => $model->id]);
    }

    public function test_delete_returns_false_if_zone_not_found()
    {
        $result = $this->repository->delete(999);
        $this->assertFalse($result);
    }
}
