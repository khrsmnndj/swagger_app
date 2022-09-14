<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cars;
use App\Models\Pilot;
use App\Models\User;

class CarsControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_cars()
    {
        $cars = Cars::factory()->count(5)->create();
        $response = $this->get('/pilot-api/v0/cars');

        $response->assertStatus(201);
        $this->assertEquals(5, $cars->count());
        // $response->assertJsonCount($cars->count(), 'data');
    }

    public function test_create_a_car()
    {
        $user = User::factory()->create();
        $pilot = Pilot::factory()->create([
            'id' => 1
        ]);
        $data = [
            'name' => 'BMW',
            'pilot_id' => '1',
        ];

        $response=$this->actingAs($user)->postJson('/pilot-api/v0/cars', $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', $data['name']);
        $response->assertJsonPath('data.pilot_id', $data['pilot_id']);
        $this->assertDatabaseHas('cars', $data);
    }

    public function test_failed_create_a_car()
    {
        $user = User::factory()->create();
        $pilot = Pilot::factory()->create([
            'id' => 1
        ]);
        $data = [
            'name' => 'BMW',
            'pilot_id' => 500,
        ];

        $response=$this->actingAs($user)->postJson('/pilot-api/v0/cars', $data);

        $response->assertStatus(422);
    }

    public function test_update_a_car()
    {
        $user = User::factory()->create();
        $car = Cars::factory()->create();

        $data = [
            'name' => 'BMW',
            'pilot_id' => $car->pilot_id
        ];

        $response=$this->actingAs($user)->putJson("/pilot-api/v0/cars/{$car->id}", $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', $data['name']);
        $response->assertJsonPath('data.pilot_id', $data['pilot_id']);
        $this->assertDatabaseHas('cars', $data);
    }

    public function test_delete_a_car()
    {
        $car = Cars::factory()->create();
        $response = $this->deleteJson("/pilot-api/v0/cars/{$car->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('cars', [
            'id' => $car->id,
        ]);
    }
}
