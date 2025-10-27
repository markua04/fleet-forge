<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $truckModels = [
            ['make' => 'Volvo', 'model' => 'FH16'],
            ['make' => 'Scania', 'model' => 'R-Series'],
            ['make' => 'MAN', 'model' => 'TGX'],
            ['make' => 'Mercedes-Benz', 'model' => 'Actros'],
            ['make' => 'DAF', 'model' => 'XF'],
            ['make' => 'Iveco', 'model' => 'S-Way'],
            ['make' => 'Freightliner', 'model' => 'Cascadia'],
            ['make' => 'Kenworth', 'model' => 'T680'],
            ['make' => 'Peterbilt', 'model' => '579'],
            ['make' => 'Mack', 'model' => 'Anthem'],
        ];

        collect($truckModels)->each(function (array $truck) {
            Vehicle::factory()->create(
                array_merge($truck, [
                    'status' => 'active',
                ])
            );
        });
    }
}
