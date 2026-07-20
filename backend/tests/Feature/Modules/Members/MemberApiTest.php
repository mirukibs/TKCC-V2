<?php

namespace Tests\Feature\Modules\Members;

use App\Modules\Members\Infrastructure\Models\MemberModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_members()
    {
        MemberModel::create(['first_name' => 'Alice', 'last_name' => 'Smith']);
        MemberModel::create(['first_name' => 'Bob', 'last_name' => 'Smith']);
        MemberModel::create(['first_name' => 'Charlie', 'last_name' => 'Smith']);

        $response = $this->getJson('/api/members');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'first_name', 'last_name', 'employment_status'],
            ]);
    }

    public function test_can_get_member()
    {
        $member = MemberModel::create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
        ]);

        $response = $this->getJson("/api/members/{$member->id}");

        $response->assertStatus(200)
            ->assertJsonPath('first_name', 'Alice');
    }

    public function test_returns_404_if_member_not_found()
    {
        $response = $this->getJson('/api/members/999');

        $response->assertStatus(404);
    }

    public function test_can_create_member()
    {
        $payload = [
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'phone' => '0712345678',
        ];

        $response = $this->postJson('/api/members', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('first_name', 'Bob');

        $this->assertDatabaseHas('members', [
            'first_name' => 'Bob',
            'last_name' => 'Builder',
        ]);
    }

    public function test_fails_validation_on_create()
    {
        $payload = [
            // missing required fields
        ];

        $response = $this->postJson('/api/members', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name']);
    }

    public function test_can_update_member()
    {
        $member = MemberModel::create([
            'first_name' => 'OldName',
            'last_name' => 'OldLast',
        ]);

        $payload = [
            'first_name' => 'NewName',
            'last_name' => 'NewLast',
        ];

        $response = $this->putJson("/api/members/{$member->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('first_name', 'NewName');

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'first_name' => 'NewName',
        ]);
    }

    public function test_can_delete_member()
    {
        $member = MemberModel::create([
            'first_name' => 'DeleteMe',
            'last_name' => 'Last',
        ]);

        $response = $this->deleteJson("/api/members/{$member->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('members', [
            'id' => $member->id,
        ]);
    }
}
