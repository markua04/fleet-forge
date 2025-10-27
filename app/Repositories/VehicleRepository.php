<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehicleRepository
{
    /**
     * Fetch a vehicle record for purchase with an update lock.
     *
     * @throws ModelNotFoundException
     */
    public function findForPurchase(int $id): Vehicle
    {
        return Vehicle::query()
            ->lockForUpdate()
            ->where('id', $id)
            ->firstOrFail();
    }
}
