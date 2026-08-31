<?php

namespace Tests\Feature\Modules\Zones\Presentation\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_zones(): void
    {
        $response = $this->getJson('/api/zones');
        $response->assertStatus(200);
    }

    public function test_can_create_zone(): void
    {
        $payload = [
            'name' => 'Zone 1',
        ];

        $response = $this->postJson('/api/zones', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('name', 'Zone 1');

        $this->assertDatabaseHas('zones', ['name' => 'Zone 1']);
    }

    public function test_can_update_zone(): void
    {
        $createResponse = $this->postJson('/api/zones', ['name' => 'Old Name']);
        $zoneId = $createResponse->json('id');

        $response = $this->putJson("/api/zones/{$zoneId}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('name', 'New Name');

        $this->assertDatabaseHas('zones', ['id' => $zoneId, 'name' => 'New Name']);
    }

    public function test_can_delete_zone(): void
    {
        $createResponse = $this->postJson('/api/zones', ['name' => 'To Delete']);
        $zoneId = $createResponse->json('id');

        $response = $this->deleteJson("/api/zones/{$zoneId}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('zones', ['id' => $zoneId]);
    }
}
