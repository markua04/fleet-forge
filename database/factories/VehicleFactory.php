<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            'Volvo', 'Scania', 'MAN', 'Mercedes-Benz', 'DAF', 'Iveco', 'Freightliner', 'Kenworth', 'Peterbilt', 'Mack'
        ];

        $model = fake()->bothify('Series ###');

        return [
            'uuid' => fake()->uuid(),
            'make' => fake()->randomElement($brands),
            'model' => $model,
            'year' => fake()->numberBetween(2010, 2025),
            'vin' => strtoupper(fake()->bothify('1#########??????')), // approximate VIN pattern
            'price' => fake()->randomFloat(2, 45000, 200000),
            'license_plate' => strtoupper(fake()->bothify('TRK-####')),
            'type' => 'truck',
            'status' => fake()->randomElement(['active', 'maintenance', 'inactive']),
            'sold_at' => null,
        ];
    }
}
