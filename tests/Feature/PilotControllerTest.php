<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cars;
use App\Models\Pilot;
use App\Models\User;

class PilotControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_pilots()
    {
        $pilots = Pilot::factory()->count(5)->create();
        $response = $this->get('/pilot-api/v0/pilots');

        $response->assertStatus(200);
        $this->assertEquals(5, $pilots->count());
        // $response->assertJsonCount($cars->count(), 'data');
    }

    public function test_create_a_pilot()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Morgan',
            'level' => 'Captain',
        ];

        $response=$this->actingAs($user)->postJson('/pilot-api/v0/pilots', $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', $data['name']);
        $response->assertJsonPath('data.level', $data['level']);
        $this->assertDatabaseHas('pilots', $data);
    }

    public function test_failed_create_a_pilot()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Morgan',
        ];

        $response=$this->actingAs($user)->postJson('/pilot-api/v0/pilots', $data);

        $response->assertStatus(422);
    }

    public function test_update_pilot()
    {
        $user = User::factory()->create();
        $pilot = Pilot::factory()->create();

        $data = [
            'name' => 'Morgan',
            'level' => 'Flight Mentor'
        ];

        $response=$this->actingAs($user)->putJson("/pilot-api/v0/pilots/{$pilot->id}", $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', $data['name']);
        $response->assertJsonPath('data.level', $data['level']);
        $this->assertDatabaseHas('pilots', $data);
    }

    public function test_delete_pilot()
    {
        $pilot = Pilot::factory()->create();
        $response = $this->deleteJson("/pilot-api/v0/pilots/{$pilot->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('pilots', [
            'id' => $pilot->id,
        ]);
    }
}
