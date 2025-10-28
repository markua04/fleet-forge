<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclesIndexApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_available_vehicles_only(): void
    {
        $available = Vehicle::factory()->count(3)->create(['sold_at' => null]);
        Vehicle::factory()->create(['sold_at' => now()]);

        $response = $this->getJson(route('api.vehicles.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data');

        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing($available->pluck('id')->all(), $ids);
    }

    public function test_vehicle_list_respects_per_page_parameter(): void
    {
        Vehicle::factory()->count(5)->create(['sold_at' => null]);

        $response = $this->getJson(route('api.vehicles.index', ['per_page' => 2]));

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2);
    }
}
