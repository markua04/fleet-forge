<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclePurchaseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_vehicle(): void
    {
        $user = User::factory()->create(['cash' => 100000]);
        $vehicle = Vehicle::factory()->create(['price' => 45000, 'sold_at' => null, 'status' => 'inactive']);

        $response = $this->postJson(route('api.users.vehicles.store', ['user' => $user->id]), [
            'vehicle_id' => $vehicle->id,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('user_vehicle', [
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'role' => 'owner',
        ]);

        $vehicle->refresh();
        $user->refresh();

        $this->assertNotNull($vehicle->sold_at);
        $this->assertEquals('active', $vehicle->status);
        $this->assertEquals(55000.00, (float) $user->cash);
    }

    public function test_vehicle_purchase_fails_when_insufficient_funds(): void
    {
        $user = User::factory()->create(['cash' => 1000]);
        $vehicle = Vehicle::factory()->create(['price' => 5000, 'sold_at' => null, 'status' => 'inactive']);

        $response = $this->postJson(route('api.users.vehicles.store', ['user' => $user->id]), [
            'vehicle_id' => $vehicle->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'You do not have enough cash to purchase this vehicle.',
            ]);

        $this->assertDatabaseMissing('user_vehicle', [
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->assertNull($vehicle->fresh()->sold_at);
        $this->assertEquals(1000.00, (float) $user->fresh()->cash);
    }

    public function test_vehicle_purchase_fails_when_vehicle_already_sold(): void
    {
        $user = User::factory()->create(['cash' => 100000]);
        $vehicle = Vehicle::factory()->create([
            'price' => 45000,
            'sold_at' => now(),
            'status' => 'active',
        ]);

        $response = $this->postJson(route('api.users.vehicles.store', ['user' => $user->id]), [
            'vehicle_id' => $vehicle->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Vehicle is no longer available for purchase.',
            ]);

        $this->assertDatabaseMissing('user_vehicle', [
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $vehicle->refresh();
        $user->refresh();

        $this->assertEquals(45000.00, (float) $vehicle->price);
        $this->assertEquals(100000.00, (float) $user->cash);
    }
}
