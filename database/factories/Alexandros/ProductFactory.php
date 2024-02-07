<?php

namespace Database\Factories\Alexandros;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alexandros\Product>
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
        $start_at = Carbon::parse(fake()->date('Y-m-d'));
        $end_at = Carbon::parse(fake()->date('Y-m-d'));
        $status = 1;
        $today = Carbon::today();
        if (($start_at && $start_at->lte($today)) || ($end_at && $today->lte($end_at))) {
            $status = 0;
        }


        return [
            'name' => fake()->text(maxNbChars:20),
            'description' => fake()->text(maxNbChars:50),
            'price' => rand(1000, 2000),
            'images' => '[]',
            'status' => $status,
            'start_at' => $start_at,
            'end_at' => $end_at,
            'customfields' => json_encode('{\"passenger\":7,\"licenseNumber\":\"sdfg\",\"syakenDate\":\"\",\"tenkenDate\":\"\",\"isSmokingAllowed\":false}')
        ];
    }
}
