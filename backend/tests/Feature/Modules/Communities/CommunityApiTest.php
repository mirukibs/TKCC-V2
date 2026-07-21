<?php

namespace Tests\Feature\Modules\Communities;

use App\Modules\Communities\Infrastructure\Models\CommunityModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommunityApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('zones')->insert([
            ['id' => 1, 'name' => 'Zone 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Zone 2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_can_list_communities()
    {
        CommunityModel::create(['name' => 'St. Peter', 'zone_id' => 1]);
        CommunityModel::create(['name' => 'St. Paul', 'zone_id' => 2]);

        $response = $this->getJson('/api/communities');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => 'St. Peter'])
            ->assertJsonFragment(['name' => 'St. Paul']);
    }

    public function test_can_create_community()
    {
        $payload = [
            'name' => 'St. Mary',
            'zone_id' => 1,
        ];

        $response = $this->postJson('/api/communities', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'St. Mary', 'zone_id' => 1]);

        $this->assertDatabaseHas('communities', ['name' => 'St. Mary']);
    }

    public function test_cannot_create_community_with_invalid_zone()
    {
        $payload = [
            'name' => 'St. Mary',
            'zone_id' => 999,
        ];

        $response = $this->postJson('/api/communities', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['zone_id']);
    }

    public function test_can_show_community()
    {
        $community = CommunityModel::create(['name' => 'St. Peter', 'zone_id' => 1]);

        $response = $this->getJson("/api/communities/{$community->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'St. Peter']);
    }

    public function test_returns_404_for_missing_community()
    {
        $response = $this->getJson('/api/communities/999');
        $response->assertStatus(404);
    }

    public function test_can_update_community()
    {
        $community = CommunityModel::create(['name' => 'St. Peter', 'zone_id' => 1]);

        $payload = [
            'name' => 'St. Peter Updated',
            'zone_id' => 2,
        ];

        $response = $this->putJson("/api/communities/{$community->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'St. Peter Updated', 'zone_id' => 2]);

        $this->assertDatabaseHas('communities', ['name' => 'St. Peter Updated']);
    }

    public function test_can_delete_community()
    {
        $community = CommunityModel::create(['name' => 'St. Peter', 'zone_id' => 1]);

        $response = $this->deleteJson("/api/communities/{$community->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('communities', ['id' => $community->id]);
    }
}
