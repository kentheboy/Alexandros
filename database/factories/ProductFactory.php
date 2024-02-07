<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(maxNbChars:20),
            'description' => fake()->text(maxNbChars:50),
            'price' => rand(1000, 2000),
            'images' => '[]',
            'start_date' => fake()->date('Y_m_d'),
            'end_date' => fake()->date('Y_m_d'),
            'customfields' => '{\"passenger\":7,\"licenseNumber\":\"sdfg\",\"syakenDate\":\"\",\"tenkenDate\":\"\",\"isSmokingAllowed\":false}'
        ];
    }
}
