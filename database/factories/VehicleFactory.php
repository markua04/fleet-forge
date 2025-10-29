<?php

declare(strict_types=1);

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $faker = $this->faker;

        if ($faker === null && class_exists(FakerFactory::class)) {
            // Lazily create a Faker instance when the shared generator is unavailable.
            $faker = FakerFactory::create();
        }

        $brands = [
            'Volvo', 'Scania', 'MAN', 'Mercedes-Benz', 'DAF', 'Iveco', 'Freightliner', 'Kenworth', 'Peterbilt', 'Mack'
        ];

        $model = $faker ? $faker->bothify('Series ###') : 'Series '.random_int(100, 999);
        $statuses = ['active', 'maintenance', 'inactive'];
        $fallbackVinSuffix = Str::upper(Str::random(6));

        return [
            'uuid' => $faker?->uuid() ?? (string) Str::uuid(),
            'make' => $faker ? $faker->randomElement($brands) : $brands[array_rand($brands)],
            'model' => $model,
            'year' => $faker ? $faker->numberBetween(2010, 2025) : random_int(2010, 2025),
            'vin' => $faker
                ? strtoupper($faker->bothify('1#########??????')) // approximate VIN pattern
                : '1'.str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT).$fallbackVinSuffix,
            'price' => $faker ? $faker->randomFloat(2, 45000, 200000) : round(random_int(4_500_000, 20_000_000) / 100, 2),
            'license_plate' => $faker
                ? strtoupper($faker->bothify('TRK-####'))
                : 'TRK-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT),
            'type' => 'truck',
            'status' => $faker ? $faker->randomElement($statuses) : $statuses[array_rand($statuses)],
            'sold_at' => null,
        ];
    }
}
