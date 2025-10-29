<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehicleRepository
{
    /**   
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

    public function paginateAvailable(int $perPage = 9): LengthAwarePaginator
    {
        return Vehicle::query()
            ->available()
            ->select([
                'id',
                'uuid',
                'make',
                'model',
                'year',
                'type',
                'price',
                'vin',
                'status',
            ])
            ->orderBy('make')
            ->orderBy('model')
            ->paginate($perPage)
            ->withQueryString();
    }
}
