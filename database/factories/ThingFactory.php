<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thing>
 */
class ThingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->name(),
            'robot_id'=>1,
            'image_path'=>fake()->imageUrl(),
            'gps'=> 'https://maps.app.goo.gl/dPWz3zVBekJpr2LM6'
        ];
    }
}
