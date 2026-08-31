<?php

namespace Tests\Feature\Sacraments;

use App\Modules\Members\Infrastructure\Models\MemberModel;
use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SacramentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_sacraments()
    {
        $member = MemberModel::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'employment_status' => 'employed',
            'marital_status' => 'single',
            'is_active' => true,
        ]);
        SacramentModel::factory()->create(['member_id' => $member->id]);

        $response = $this->getJson('/api/sacraments');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_can_create_sacrament()
    {
        $member = MemberModel::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'employment_status' => 'employed',
            'marital_status' => 'single',
            'is_active' => true,
        ]);

        $payload = [
            'member_id' => $member->id,
            'baptism_status' => 'baptized',
            'baptism_date' => '2020-01-01',
            'baptism_place' => 'Dar es Salaam',
            'confirmation_status' => 'confirmed',
            'confirmation_date' => '2021-01-01',
            'confirmation_place' => 'Dodoma',
            'marriage_status' => 'single',
        ];

        $response = $this->postJson('/api/sacraments', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['baptism_place' => 'Dar es Salaam']);

        $this->assertDatabaseHas('sacraments', ['member_id' => $member->id, 'baptism_status' => 'baptized']);
    }

    public function test_can_get_sacrament_by_member_id()
    {
        $member = MemberModel::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'employment_status' => 'employed',
            'marital_status' => 'single',
            'is_active' => true,
        ]);
        $sacrament = SacramentModel::factory()->create(['member_id' => $member->id]);

        $response = $this->getJson('/api/sacraments/member/'.$member->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $sacrament->id]);
    }
}
