<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pilot;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cars>
 */
class CarsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $name => $this->faker->word;
        // $pilot_id => $this->faker->randomDigit;
        return [
            "name" => fake()->name(),
            "pilot_id" => Pilot::factory(),
        ];
    }
}
