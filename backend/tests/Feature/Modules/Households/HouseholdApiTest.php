<?php

namespace Tests\Feature\Modules\Households;

use App\Modules\Households\Infrastructure\Models\HouseholdModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HouseholdApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_households()
    {
        DB::table('communities')->insert(['id' => 1, 'name' => 'Test Community']);

        HouseholdModel::create([
            'name' => 'The Doe Family',
            'community_id' => 1,
            'ownership' => 'owned',
            'leader_id' => null,
        ]);

        $response = $this->getJson('/api/households');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The Doe Family'])
            ->assertJsonCount(1);
    }

    public function test_can_create_household()
    {
        DB::table('communities')->insert(['id' => 1, 'name' => 'Test Community']);

        $data = [
            'name' => 'The Smith Family',
            'community_id' => 1,
            'ownership' => 'rented',
        ];

        $response = $this->postJson('/api/households', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'The Smith Family']);

        $this->assertDatabaseHas('households', [
            'name' => 'The Smith Family',
            'ownership' => 'rented',
        ]);
    }

    public function test_cannot_create_household_with_invalid_data()
    {
        $data = [
            // Missing name
            'community_id' => 1,
            'ownership' => 'rented',
        ];

        $response = $this->postJson('/api/households', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_household()
    {
        DB::table('communities')->insert(['id' => 2, 'name' => 'Test Community 2']);

        $household = HouseholdModel::create([
            'name' => 'The Johnson Family',
            'community_id' => 2,
            'ownership' => 'owned',
            'leader_id' => null,
        ]);

        $response = $this->getJson("/api/households/{$household->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The Johnson Family']);
    }

    public function test_returns_404_for_invalid_household()
    {
        $response = $this->getJson('/api/households/999');

        $response->assertStatus(404);
    }

    public function test_can_update_household()
    {
        DB::table('communities')->insert(['id' => 3, 'name' => 'Test Community 3']);

        $household = HouseholdModel::create([
            'name' => 'The Old Name',
            'community_id' => 3,
            'ownership' => 'owned',
            'leader_id' => null,
        ]);

        $data = [
            'name' => 'The New Name',
            'community_id' => 3,
            'ownership' => 'rented',
        ];

        $response = $this->putJson("/api/households/{$household->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The New Name']);

        $this->assertDatabaseHas('households', [
            'id' => $household->id,
            'name' => 'The New Name',
            'ownership' => 'rented',
        ]);
    }

    public function test_cannot_update_household_with_invalid_data()
    {
        DB::table('communities')->insert(['id' => 4, 'name' => 'Test Community 4']);

        $household = HouseholdModel::create([
            'name' => 'The Old Name',
            'community_id' => 4,
            'ownership' => 'owned',
            'leader_id' => null,
        ]);

        $data = [
            // Missing required name
            'community_id' => 4,
            'ownership' => 'rented',
        ];

        $response = $this->putJson("/api/households/{$household->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_delete_household()
    {
        DB::table('communities')->insert(['id' => 5, 'name' => 'Test Community 5']);

        $household = HouseholdModel::create([
            'name' => 'To Delete',
            'community_id' => 5,
            'ownership' => 'owned',
            'leader_id' => null,
        ]);

        $response = $this->deleteJson("/api/households/{$household->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('households', [
            'id' => $household->id,
        ]);
    }
}
