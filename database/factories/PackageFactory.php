<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100000, 300000),
            'speed' => $this->faker->randomElement(['10 Mbps', '20 Mbps']),
            'description' => $this->faker->paragraph(),
        ];
    }
}
