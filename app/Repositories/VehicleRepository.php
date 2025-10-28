<?php

declare(strict_types=1);

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
            ->available()
            ->whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();
    }
}
